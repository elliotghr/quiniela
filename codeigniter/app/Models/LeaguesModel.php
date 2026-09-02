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
        return $mongoLib->getEntryList();
    }

    public function getQuinielaLeagues()
    {
        $QuinielasModel = new \App\Models\QuinielasModel();
        return $QuinielasModel->getQuinielaLeagues();
    }

    public function getFixtures($league)
    {
        $response = file_get_contents(WRITEPATH . "data/fixtures/{$league['id']}-{$league['season']}.json");
        $data = json_decode($response, true);

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
    public function getMultipleFixtures(array $leagues)
    {
        $fixtures = [];
        foreach ($leagues['ids'] as $league) {
            $response = file_get_contents(WRITEPATH . "data/fixtures/{$league["liga"]}-{$league['temporada']}.json");
            $data = json_decode($response, true);

            foreach ($data["response"] as $fixture) {
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
        $response = file_get_contents(WRITEPATH . "data/fixtures/{$league['id']}-{$league['season']}.json");
        $data = json_decode($response, true);

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
}
