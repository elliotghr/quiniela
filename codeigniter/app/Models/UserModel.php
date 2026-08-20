<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public function getUser($usr)
    {
        $sql = "SELECT  U.*,
                        DU.nombre AS 'nombre',
                        DU.apellido_paterno AS 'apellido_paterno',
                        DU.apellido_materno AS 'apellido_materno',
                        DU.avatar AS 'avatar'
                FROM    Usuario U
                INNER JOIN Datos_Usuario DU ON
                        DU.usuario_id = U.id
                WHERE   U.usuario = :user:";

        $query = $this->db->query($sql, ['user' => $usr]);

        return $query;
    }

    public function getUsers()
    {
        $sql = "SELECT 	U.id AS 'usuario_id',
                        U.usuario AS 'usuario',
                        U.clave AS 'clave',
                        DU.nombre AS 'nombre',
                        DU.apellido_paterno AS 'apellido_paterno',
                        DU.apellido_materno AS 'apellido_materno',
                        DU.fecha_nacimiento AS 'fecha_nacimiento',
                        DU.avatar AS 'avatar',
                        R.descripcion AS 'rol'
                FROM 	Usuario U
                INNER JOIN Datos_Usuario DU ON
                        DU.usuario_id = U.id
                INNER JOIN Rol R ON
                        U.rol_id = R.id";

        $query = $this->db->query($sql);

        return $query;
    }

    public function getCurrentUser()
    {
        $id = getUserSession();

        $sql = "SELECT  *
                FROM    Usuario U
                WHERE   U.id = :id:";

        $query = $this->db->query($sql, ['id' => $id]);

        return $query;
    }

    public function getUserData()
    {
        $id = getUserSession();

        $sql = "SELECT 	R.descripcion AS 'rol',
                        U.usuario,
                        DU.nombre,
                        DU.apellido_paterno,
                        DU.apellido_materno,
                        DU.fecha_nacimiento,
                        DU.avatar AS 'avatar',
                        TIMESTAMPDIFF(YEAR, DU.fecha_nacimiento, NOW()) AS 'edad'
                FROM 	Usuario U
                INNER JOIN Datos_Usuario DU ON
                        U.id = DU.usuario_id
                INNER JOIN Rol R ON
                        R.id = U.rol_id
                WHERE   U.id = :id:";

        $query = $this->db->query($sql, ['id' => $id]);

        return $query;
    }

    public function getUserDataById($id)
    {
        $sql = "SELECT 	U.id AS 'usuario_id',
                        DU.id AS 'datos_usuario_id',
                        R.id AS 'rol_id',
                        U.usuario AS 'correo',
                        DU.nombre,
                        DU.apellido_paterno,
                        DU.apellido_materno,
                        DU.fecha_nacimiento,
                        U.primera_vez,
                        U.cambio_clave,
                        U.fecha_cambio_clave
                FROM 	Usuario U
                INNER JOIN Datos_Usuario DU ON
                        U.id = DU.usuario_id
                INNER JOIN Rol R ON
                        R.id = U.rol_id
                WHERE   U.id = :id:";

        $query = $this->db->query($sql, ['id' => $id]);

        return $query;
    }

    public function savePassword($id, $pass)
    {
        $data = [
                'clave' => hashPassword($pass),
                'primera_vez' => false
                ];

        $builder = $this->db->table('Usuario');
            
        $builder->where('id', $id);
        $builder->update($data);
    }

    public function getMenu()
    {
        $userId = getUserSession();

        $sql = "SELECT	U.id AS 'usuario_id',
                        M.id AS 'modulo_id',
                        M.modulo_padre_id,
                        M.titulo,
                        M.url,
                        M.icono,
                        M.orden,
                        MR.escritura
                FROM	Modulo_Rol MR
                INNER JOIN Modulo M ON
                        M.id = MR.modulo_id
                INNER JOIN Rol R ON
                        R.id = MR.rol_id
                INNER JOIN Usuario U ON
                        U.rol_id = R.id
                WHERE U.id = :userId:
                ORDER BY M.modulo_padre_id, M.orden";

        $query = $this->db->query($sql, ['userId' => $userId]);

        return $query;
    }

    function newUser($user, $dataUser)
    {
        $this->db->transStart();

        $builder = $this->db->table('Usuario');
        $builder->insert($user);

        $dataUser['usuario_id'] = $this->db->insertID();

        $builder = $this->db->table('Datos_Usuario');
        $builder->insert($dataUser);
        
        $this->db->transComplete();
    }

    public function deleteUser($user)
    {
        $this->db->transStart();

        $builder = $this->db->table('Datos_Usuario');
        $builder->where('usuario_id', $user['usuario_id']);
        $builder->delete();

        $builder = $this->db->table('Usuario');
        $builder->where('id', $user['usuario_id']);
        $builder->delete();

        $this->db->transComplete();
    }

    function saveUser($user, $dataUser = null)
    {
        $this->db->transStart();

        $builder = $this->db->table('Usuario');
        $builder->where('id', $user['id']);
        $builder->update($user);

        if(isset($dataUser))
        {
			$builder = $this->db->table('Datos_Usuario');
			$builder->where('id', $dataUser['id']);
			$builder->update($dataUser);
        }
        
        $this->db->transComplete();
    }

	function saveDataUser($dataUser)
    {
        $this->db->transStart();

		$builder = $this->db->table('Datos_Usuario');
		$builder->where('usuario_id', $dataUser['usuario_id']);
		$builder->update($dataUser);
        
        $this->db->transComplete();
    }
}
