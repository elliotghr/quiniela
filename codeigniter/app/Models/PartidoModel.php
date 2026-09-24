<?php

namespace App\Models;

use CodeIgniter\Model;

class PartidoModel extends Model
{
    protected $table      = 'Partido';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'pronostico_id',
        'partido',
        'pronostico_local',
        'pronostico_visitante',
        'jornada',
        'puntos',
        'fecha_creacion',
        'fecha_cambio'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_cambio';
    // protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPuntosPorJornada($quinielaId)
    {
        $db = \Config\Database::connect();
        return $db->query('
        SELECT
			U.nombre as nombre,
            R.usuario_id,
            R.nombre AS nombre_jornada,
            orden,
            puntos_jornada,
            SUM(puntos_jornada) OVER (
                PARTITION BY usuario_id
                ORDER BY orden
            ) AS puntos_acumulados
        FROM (
            SELECT 
                PCO.usuario_id,
                SUM(P.puntos) AS puntos_jornada,
                P.jornada AS jornada_id,
                J.nombre,
                J.orden
            FROM Partido P
            INNER JOIN Jornada J
                ON P.jornada = J.id
            INNER JOIN Pronostico PCO
                ON PCO.id = P.pronostico_id
            WHERE PCO.quiniela_id = (' . intval($quinielaId) . ')
            GROUP BY
                P.jornada,
                P.pronostico_id,
                PCO.usuario_id,
                J.nombre,
                J.orden
        ) AS R
        INNER JOIN Datos_Usuario U ON U.usuario_id = R.usuario_id
        ORDER BY
            orden,
            usuario_id;
        ');
    }
}
