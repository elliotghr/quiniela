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
        // Obtener los partidos que tienen pronósticos registrados
        // $partidosSinPuntos = $this->partidoModel->where(['pronostico_local !=' => null, 'pronostico_visitante !=' => null])->findAll();


        // Generar un diccionario de id, id_partido, pronostico_local y pronostico_visitante
        $partidosDict = [];
        foreach ($partidosSinPuntos as $partido) {
            $partidosDict[$partido['id']] = [
                'id' => $partido['id'],
                'partido' => $partido['partido'],
                'pronostico_local' => $partido['pronostico_local'],
                'pronostico_visitante' => $partido['pronostico_visitante']
            ];
        }

        echo "<h2>Partidos Dict</h2>";
        echo "<pre>";
        print_r($partidosDict);
        echo "</pre>";

        // Generar un diccionario de id, id_partido, pronostico_local y pronostico_visitante
        $partidosIds = array_column($partidosSinPuntos, 'partido');
        $fixturesId = array_values(array_unique(array_map('intval', $partidosIds)));

        echo "<h2>Fixtures IDs from SQL</h2>";
        echo "<pre>";
        print_r($partidosIds);
        echo "</pre>";

        // Obtener todos los partidos de mongo que estén terminados y cuyos IDs estén en $partidosIds
        $fixturesMongo = $this->leaguesModel->getFixturesById($fixturesId);

        echo "<h2>Fixtures from MongoDB</h2>";
        // Imprimir los fixtures obtenidos de MongoDB
        echo "<pre>";
        print_r($fixturesMongo);
        echo "</pre>";

        // Obtener el catálogo de jornadas de Mysql
        $jornadas = $this->jornadaModel->findAll();

        echo "<pre>";
        print_r($jornadas);
        echo "</pre>";
        // return;
        // Actualizar los puntos de los partidos y la jornada en la base de datos
        foreach ($partidosDict as $partido) {
            // Obtener el ID del fixture correspondiente al partido (SQL)
            $fixtureId = intval($partido['partido']);
            // Verificar si el fixture correspondiente existe en MongoDB
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
                // Si el pronóstico coincide exactamente con el resultado del partido, se otorgan 3 puntos
                if ($homeGoals == $partido['pronostico_local'] && $awayGoals == $partido['pronostico_visitante']) {
                    $puntos = 3;
                // Si el pronóstico no coincide exactamente pero acierta el resultado (ganador o empate), se otorga 1 punto
                } elseif (($homeGoals > $awayGoals && $partido['pronostico_local'] > $partido['pronostico_visitante']) ||
                          ($homeGoals < $awayGoals && $partido['pronostico_local'] < $partido['pronostico_visitante']) ||
                          ($homeGoals == $awayGoals && $partido['pronostico_local'] == $partido['pronostico_visitante'])) {
                    $puntos = 1;
                }

                $this->partidoModel->update($partido['id'], ['puntos' => $puntos, 'jornada' => $round]);
            }
        }
    }
}
