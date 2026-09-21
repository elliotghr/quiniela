<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\MongoLib;

class LeaguesModel extends Model
{
    public function getLeagues()
    {
        /**
         * Obtiene la lista de ligas desde la base de datos MongoDB utilizando la librería MongoLib.
         */
        $mongoLib = new MongoLib("quiniela", "ligas");
        $getLigas = $mongoLib->getEntryList();

        // Convertir el array de ligas en un array asociativo con el ID como clave
        $data['leagues'] = [];
        foreach ($getLigas as $resultado) {
            $data['leagues'][$resultado['id']] = $resultado;
        }
        return $data['leagues'];
    }

    public function getQuinielaLeagues()
    {
        $QuinielasModel = new \App\Models\QuinielasModel();
        return $QuinielasModel->getQuinielaLeagues();
    }

    public function getFixtures($league)
    {

        $mongoLib = new MongoLib("quiniela", "partidos");
        $response = $mongoLib->getEntry([
            "parameters.league" => $league['id'],
            "parameters.season" => $league['season']
        ]);

        $data = json_decode(json_encode($response), true);

        $fixtures = [];

        foreach ($data["response"] as $fixture) {
            if ($fixture["league"]["id"] == $league['id'] && $fixture["league"]["season"] == $league['season']) {
                $fixtures[$fixture["fixture"]["id"]]["id"] = $fixture["fixture"]["id"];
                $fixtures[$fixture["fixture"]["id"]]["date"] = $fixture["fixture"]["date"];
                $fixtures[$fixture["fixture"]["id"]]["home_name"] = $fixture["teams"]['home']["name"];
                $fixtures[$fixture["fixture"]["id"]]["home_logo"] = $fixture["teams"]['home']["logo"];
                $fixtures[$fixture["fixture"]["id"]]["home_goals"] = $fixture["goals"]['home'];
                $fixtures[$fixture["fixture"]["id"]]["away_name"] = $fixture["teams"]['away']["name"];
                $fixtures[$fixture["fixture"]["id"]]["away_logo"] = $fixture["teams"]['away']["logo"];
                $fixtures[$fixture["fixture"]["id"]]["away_goals"] = $fixture["goals"]['away'];
                $fixtures[$fixture["fixture"]["id"]]["round"] = $fixture["league"]['round'];
            }
        }

        return $fixtures;
    }
    public function getBdUpcomingFixtures(array $leagues)
    {
        $fixtures = [];
        foreach ($leagues['ids'] as $league) {
            $mongoLib = new MongoLib("quiniela", "partidos");

            $timestampActual = time();
            $timestampUpcomingLimit = $timestampActual + (8 * 24 * 60 * 60);

            $pipeline = [
                [
                    '$match' => [
                        'parameters.league' => $league['liga'],
                        'parameters.season' => $league['temporada']
                    ]
                ],
                [
                    '$unwind' => '$response'
                ],
                [
                    '$match' => [
                        'response.fixture.timestamp' => [
                            '$gte' => $timestampActual,
                            '$lte' => $timestampUpcomingLimit
                        ]
                    ]
                ],
                [
                    '$replaceRoot' => [
                        'newRoot' => '$response'
                    ]
                ]
            ];

            $resultado = $mongoLib->aggregate($pipeline);

            foreach ($resultado as $fixture) {
                if ($fixture["league"]["id"] == $league['liga'] && $fixture["league"]["season"] == $league['temporada']) {
                    $fixtures[$fixture["fixture"]["id"]]["id"] = $fixture["fixture"]["id"];
                    $fixtures[$fixture["fixture"]["id"]]["date"] = $fixture["fixture"]["date"];
                    $fixtures[$fixture["fixture"]["id"]]["home_name"] = $fixture["teams"]['home']["name"];
                    $fixtures[$fixture["fixture"]["id"]]["home_logo"] = $fixture["teams"]['home']["logo"];
                    $fixtures[$fixture["fixture"]["id"]]["home_goals"] = $fixture["goals"]['home'];
                    $fixtures[$fixture["fixture"]["id"]]["away_name"] = $fixture["teams"]['away']["name"];
                    $fixtures[$fixture["fixture"]["id"]]["away_logo"] = $fixture["teams"]['away']["logo"];
                    $fixtures[$fixture["fixture"]["id"]]["away_goals"] = $fixture["goals"]['away'];
                    $fixtures[$fixture["fixture"]["id"]]["round"] = $fixture["league"]['round'];
                }
            }
        }
        return $fixtures;
    }

    public function getFixture($league)
    {
        $mongoLib = new MongoLib("quiniela", "partidos");
        $response = $mongoLib->getEntry([
            "parameters.league" => $league['id'],
            "parameters.season" => $league['season']
        ]);

        $data = json_decode(json_encode($response), true);

        $fixtures = [];

        foreach ($data["response"] as $fixture) {
            if ($fixture["league"]["id"] == $league['id'] && $fixture["league"]["season"] == $league['season'] && $fixture["fixture"]["id"] == $league['fixture']) {
                $fixtures[$fixture["fixture"]["id"]]["id"] = $fixture["fixture"]["id"];
                $fixtures[$fixture["fixture"]["id"]]["date"] = $fixture["fixture"]["date"];
                $fixtures[$fixture["fixture"]["id"]]["home_name"] = $fixture["teams"]['home']["name"];
                $fixtures[$fixture["fixture"]["id"]]["home_logo"] = $fixture["teams"]['home']["logo"];
                $fixtures[$fixture["fixture"]["id"]]["home_goals"] = $fixture["goals"]['home'];
                $fixtures[$fixture["fixture"]["id"]]["away_name"] = $fixture["teams"]['away']["name"];
                $fixtures[$fixture["fixture"]["id"]]["away_logo"] = $fixture["teams"]['away']["logo"];
                $fixtures[$fixture["fixture"]["id"]]["away_goals"] = $fixture["goals"]['away'];
            }
        }

        return $fixtures;
    }

    public function getFixturesById(array $fixturesId)
    {
        // print_r($fixturesId);

        $mongoLib = new MongoLib("quiniela", "partidos");
        $response = $mongoLib->aggregate([
            [
                '$unwind' => '$response'
            ],
            [
                '$match' => [
                    'response.fixture.id' => [
                        '$in' => $fixturesId
                    ],
                    'response.fixture.status.short' => 'FT'
                ]
            ],
            [
                '$replaceRoot' => [
                    'newRoot' => '$response'
                ]
            ]
        ]);

        $data = iterator_to_array($response);

        $fixtures = [];

        foreach ($data as $fixture) {
            if (in_array($fixture["fixture"]["id"], $fixturesId)) {
                $fixtures[$fixture["fixture"]["id"]]["id"] = $fixture["fixture"]["id"];
                $fixtures[$fixture["fixture"]["id"]]["home_goals"] = $fixture["goals"]['home'];
                $fixtures[$fixture["fixture"]["id"]]["away_goals"] = $fixture["goals"]['away'];
                $fixtures[$fixture["fixture"]["id"]]["round"] = $fixture["league"]["round"];
            }
        }

        return $fixtures;
    }
}
