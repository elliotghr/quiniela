<?php

namespace App\Controllers\Sync;

use App\Controllers\BaseController;
use App\Models\LeaguesModel;
use App\Models\PartidoModel;
use App\Models\JornadaModel;

class SyncController extends BaseController
{
    function __construct()
    {
        $this->leaguesModel = new LeaguesModel();
        $this->partidoModel = new PartidoModel();
        $this->jornadaModel = new JornadaModel();
    }

    function syncPuntos()
    {
        // Obtener todos los partidos que son NULL en el campo 'puntos'
        $partidosSinPuntos = $this->partidoModel->where('puntos', null)->findAll();

        // Obtener el campo 'partido' de los partidos sin puntos
        $partidosIds = array_column($partidosSinPuntos, 'partido');
        $fixturesId = array_values(array_unique(array_map('intval', $partidosIds)));

        // Obtener todos los partidos de mongo que estén terminados y cuyos IDs estén en $partidosIds
        $fixturesMongo = $this->leaguesModel->getFixturesById($fixturesId);

        // Imprimir los fixtures obtenidos de MongoDB
        echo "<pre>";
        print_r($fixturesMongo);
        echo "</pre>";

        // Obtener el catálogo de jornadas de Mysql
        $jornadas = $this->jornadaModel->findAll();

        echo "<pre>";
        print_r($jornadas);
        echo "</pre>";

        // Actualizar los puntos de los partidos y la jornada en la base de datos
        foreach ($partidosSinPuntos as $partido) {
            $fixtureId = intval($partido['partido']);
            if (isset($fixturesMongo[$fixtureId])) {
                $homeGoals = $fixturesMongo[$fixtureId]['home_goals'];
                $awayGoals = $fixturesMongo[$fixtureId]['away_goals'];
                $round = $fixturesMongo[$fixtureId]['round'];

                $jornada_filter = array_filter($jornadas, function($j) use ($round) {
                    return $j["nombre"] == $round;
                });

                if (!empty($jornada_filter)) {
                    $jornada = array_shift($jornada_filter);
                    echo "jornada encontrada:   " . $jornada["nombre"] . "\n";
                    $round = $jornada["id"];
                    echo "jornada actualizada a id:   " . $round . "\n";
                }else{
                    echo "jornada no encontrada para el round:   " . $round . "\n";
                    // Insertar una nueva jornada en la base de datos y actualizar el array de jornadass
                    $data = ['nombre' => $round, 'orden' => 0, 'fecha_creacion' => date('Y-m-d H:i:s')];
                    $this->jornadaModel->insert($data);
                    $round = $this->jornadaModel->getInsertID();
                    // Agregar la nueva jornada al array de jornadas para futuras referencias
                    $jornadas[] = ['id' => $round, 'nombre' => $data['nombre'], 'orden' => $data['orden'], 'fecha_creacion' => $data['fecha_creacion']];
                }

                $puntos = 0;
                if ($homeGoals > $awayGoals) {
                    $puntos = 3;
                } elseif ($homeGoals == $awayGoals) {
                    $puntos = 1;
                }

                $this->partidoModel->update($partido['id'], ['puntos' => $puntos, 'jornada' => $round]);
            }
        }
    }
}
