<?php

namespace App\Models;

use CodeIgniter\Model;

class ModuleModel extends Model
{
    public function getModules()
    {
        $sql = "SELECT	*
                FROM 	Modulo
                ORDER BY modulo_padre_id, orden";

        $query = $this->db->query($sql);

        return $query;
    }

    public function getModulesByRolId($id)
    {
        $sql = "SELECT	MR.id AS 'modulo_rol_id',
                        MR.escritura AS 'modulo_rol_escritura',
                        M.id AS 'modulo_id',
                        M.descripcion AS 'modulo_descripcion',
                        M.icono AS 'modulo_icono',
                        M.orden AS 'modulo_orden',
                        R.id AS 'rol_id',
                        R.descripcion AS 'rol_descripcion'
                FROM 	Modulo_Rol MR
                INNER JOIN Modulo M ON
                        M.id = MR.modulo_id
                INNER JOIN Rol R ON
                        R.id = MR.rol_id
                WHERE R.id = :rolId:";

        $query = $this->db->query($sql, ['rolId' => $id]);

        return $query;
    }

    public function saveAccess($rolId, $accessArray)
    {
        $this->db->transStart();

        $builder = $this->db->table('Modulo_Rol');
        $builder->where('rol_id', $rolId);
        $builder->delete();

        $builder = $this->db->table('Modulo_Rol');
        $builder->insertBatch($accessArray);
        
        $this->db->transComplete();
    }
}
