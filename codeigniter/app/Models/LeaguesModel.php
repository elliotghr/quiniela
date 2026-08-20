<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaguesModel extends Model
{
    public function getLeagues()
    {
        /**
         * Obtiene la lista de ligas desde el archivo JSON generado en el Convert.py y devuelve un arreglo asociativo con la información de cada liga.
         */
        $response = file_get_contents(WRITEPATH . "data/leagues.json");
        $items = json_decode($response, true);

        $leagues = [];
        foreach ($items as $item) {
            $id = $item["id"];
            $leagues[$id]["name"]  = $item["name"];
            $leagues[$id]["logo"]  = $item["logo"];
            $leagues[$id]["start"] = $item["start"];
            $leagues[$id]["end"]   = $item["end"];
        }

        return $leagues;
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
            if ($fixture["league"]["id"] == $league['id'] && $fixture["league"]["season"] == $league['season'] && in_array($fixture["league"]["round"], $league['rounds']) && $fixture["fixture"]["id"] == $league['fixture']) {
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
