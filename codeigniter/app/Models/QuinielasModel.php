<?php

namespace App\Models;

use CodeIgniter\Model;

class QuinielasModel extends Model
{
    public function getQuinielaLeagues()
    {
        $sql = "SELECT q.liga, q.temporada
        FROM Quiniela q 
        INNER JOIN Pronostico p 
        ON p.quiniela_id = q.id 
        WHERE p.usuario_id = :usuario_id:;";

        $query = $this->db->query($sql, ['usuario_id' => getUserSession()]);

        return $query->getResultArray();
    }

    public function getParticipantesPartidos($quiniela)
    {
        $sql = "SELECT	Q.id AS 'quiniela_id',
                        DU.usuario_id,
                        Q.liga AS 'quiniela_liga',
                        Q.temporada AS 'quiniela_temporada',
                        Q.rondas AS 'quiniela_ronda',
                        PCO.consecutivo AS 'pronostico_consecutivo',
                        P.partido AS 'partido_partido',
                        P.pronostico_local AS 'partido_pronostico_local',
                        P.pronostico_visitante AS 'partido_pronostico_visitante',
                        DU.nombre AS 'usuario_nombre',
                        DU.apellido_paterno AS 'usuario_apellido_paterno',
                        DU.apellido_materno AS 'usuario_apellido_materno',
                        DU.avatar AS 'usuario_avatar'
                FROM	Quiniela Q
                INNER JOIN Pronostico PCO ON
                        PCO.quiniela_id = Q.id
                INNER JOIN Partido P ON
                        P.pronostico_id = PCO.id
                INNER JOIN Datos_Usuario DU ON
                        DU.usuario_id = PCO.usuario_id
                WHERE Q.id = :quiniela_id:";

        $query = $this->db->query($sql, $quiniela);

        return $query;
    }
    public function getParticipantes($quiniela)
    {
        $sql = "SELECT	PCO.id AS 'pronostico_id',
						PCO.quiniela_id AS 'quiniela_id',
						PCO.usuario_id AS 'usuario_id',
						U.usuario AS 'usuario_usuario',
						DU.nombre AS 'usuario_nombre',
						DU.apellido_paterno AS 'usuario_apellido_paterno',
						DU.apellido_materno AS 'usuario_apellido_materno',
						DU.avatar AS 'usuario_avatar',
						PCO.activo AS 'pronostico_activo',
						PCO.consecutivo AS 'pronostico_consecutivo'
				FROM	Pronostico PCO
				INNER JOIN Usuario U ON
						U.id = PCO.usuario_id
				INNER JOIN Datos_Usuario DU ON
						DU.usuario_id = U.id
				WHERE	PCO.quiniela_id = :quiniela_id:
				ORDER BY DU.nombre, DU.apellido_paterno, DU.apellido_materno, U.usuario, PCO.consecutivo ASC";

        $query = $this->db->query($sql, $quiniela);

        return $query;
    }

    public function getQuinielas($id)
    {
        $sql = "SELECT  Q.id AS 'quiniela_id',
                        Q.usuario_id AS 'quiniela_usuario_id',
                        Q.tipo_quiniela_id,
                        Q.fecha_inicio,
                        Q.nombre AS 'quiniela_nombre',
                        Q.liga,
                        Q.temporada,
                        Q.rondas,
                        Q.fecha_creacion
                FROM    Quiniela Q
                INNER JOIN Pronostico PCO ON
                        PCO.quiniela_id = Q.id
                WHERE   PCO.usuario_id = :id:
                GROUP BY Q.id
                ORDER BY Q.fecha_creacion DESC";

        $query = $this->db->query($sql, ['id' => $id]);

        return $query;
    }

    public function getQuiniela($quiniela)
    {
        $sql = "SELECT  Q.id AS 'quiniela_id',
                        Q.usuario_id AS 'quiniela_usuario_id',
                        Q.tipo_quiniela_id,
                        Q.fecha_inicio,
                        Q.nombre AS 'quiniela_nombre',
                        Q.liga,
                        Q.temporada,
                        Q.rondas,
                        Q.fecha_creacion,
                        Q.url_encode
                FROM    Quiniela Q
                INNER JOIN Pronostico PCO ON
                        PCO.quiniela_id = Q.id
                WHERE   PCO.usuario_id = :usuario_id:
                        AND Q.id = :quiniela_id:
                GROUP BY Q.id";

        $query = $this->db->query($sql, $quiniela);

        return $query;
    }

    public function getQuinielaById($quiniela)
    {
        $sql = "SELECT  Q.id AS 'quiniela_id',
                        Q.usuario_id AS 'quiniela_usuario_id',
                        Q.tipo_quiniela_id,
                        Q.fecha_inicio,
                        Q.nombre AS 'quiniela_nombre',
                        Q.liga,
                        Q.temporada,
                        Q.rondas,
                        Q.fecha_creacion,
                        Q.url_encode,
                        Q.max_pronosticos
                FROM    Quiniela Q
                WHERE   Q.id = :quiniela_id:";

        $query = $this->db->query($sql, $quiniela);

        return $query;
    }

    public function getQuinielaByUrlEncode($quiniela)
    {
        $sql = "SELECT  Q.id AS 'quiniela_id',
                        Q.usuario_id AS 'quiniela_usuario_id',
                        Q.tipo_quiniela_id,
                        Q.fecha_inicio,
                        Q.nombre AS 'quiniela_nombre',
                        Q.liga,
                        Q.temporada,
                        Q.rondas,
                        Q.fecha_creacion,
                        Q.url_encode,
                        Q.max_pronosticos
                FROM    Quiniela Q
                WHERE   Q.url_encode = :url_encode:";

        $query = $this->db->query($sql, $quiniela);

        return $query;
    }

    public function getPronostico($pronostico)
    {
        $sql = "SELECT	PCO.id AS 'pronostico_id',
                        PCO.quiniela_id AS 'quiniela_id',
                        PCO.usuario_id AS 'usuario_id',
						PCO.consecutivo,
						PCO.activo
                FROM	Pronostico PCO
                WHERE	PCO.id = :pronostico_id:
                ORDER BY id ASC";

        $query = $this->db->query($sql, $pronostico);

        return $query;
    }

    public function getPronosticos($pronosticos)
    {
        $sql = "SELECT	PCO.id AS 'pronostico_id',
                        PCO.quiniela_id AS 'quiniela_id',
                        PCO.usuario_id AS 'usuario_id',
						PCO.consecutivo
                FROM	Pronostico PCO
                WHERE	PCO.quiniela_id = :quiniela_id:
                        AND PCO.usuario_id = :usuario_id:
                ORDER BY id ASC";

        $query = $this->db->query($sql, $pronosticos);

        return $query;
    }

    public function getPartidos($quiniela)
    {
        $sql = "SELECT  P.id AS 'partido_id',
                        P.partido,
                        P.pronostico_local,
                        P.pronostico_visitante
                FROM    Partido P
                INNER JOIN Pronostico PCO ON
                        PCO.id = P.pronostico_id
                WHERE   PCO.id = :pronostico_id:
                        AND PCO.quiniela_id = :quiniela_id:
                        AND PCO.usuario_id = :usuario_id:";

        $query = $this->db->query($sql, $quiniela);

        return $query;
    }

    public function newQuiniela($quiniela)
    {
        $builder = $this->db->table('Quiniela');
        $builder->insert($quiniela);
        return $this->db->insertID();
    }

    public function updateQuiniela($quiniela)
    {
        $builder = $this->db->table('Quiniela');
        $builder->where('id', $quiniela['id']);
        $builder->where('usuario_id', $quiniela['usuario_id']);
        unset($quiniela['id'], $quiniela['usuario_id']);
        $builder->update($quiniela);
    }

    public function deleteQuiniela($quiniela)
    {
        // Obtener los pronostico_ids de esta quiniela para borrar Partido
        $pronosticoIds = $this->db->table('Pronostico')
            ->select('id')
            ->where('quiniela_id', $quiniela['id'])
            ->get()
            ->getResultArray();

        if (!empty($pronosticoIds)) {
            $ids = array_column($pronosticoIds, 'id');
            $this->db->table('Partido')->whereIn('pronostico_id', $ids)->delete();
        }

        $this->db->table('Pronostico')->where('quiniela_id', $quiniela['id'])->delete();
        $this->db->table('Bloque')->where('quiniela_id', $quiniela['id'])->delete();

        $builder = $this->db->table('Quiniela');
        $builder->where('id', $quiniela['id']);
        $builder->where('usuario_id', $quiniela['usuario_id']);
        return $builder->delete();
    }

    public function newPartidos($partidos)
    {
        $builder = $this->db->table('Partido');
        $builder->insertBatch($partidos);
    }

    public function newPronosticos($pronosticos)
    {
        $builder = $this->db->table('Pronostico');
        $builder->insertBatch($pronosticos);
    }

    public function savePartidos($partidos)
    {
        $builder = $this->db->table('Partido');
        $builder->updateBatch($partidos, 'id');
    }

    public function savePronostico($pronostico)
    {
        $builder = $this->db->table('Pronostico');
        $builder->where('id', $pronostico['id']);
        $builder->where('quiniela_id', $pronostico['quiniela_id']);
        $builder->update($pronostico);
    }
}
