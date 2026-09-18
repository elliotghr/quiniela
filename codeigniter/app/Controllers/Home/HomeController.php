<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\QuinielasModel;
use App\Models\LeaguesModel;

class HomeController extends BaseController
{
    function __construct()
    {
        $this->quinielasModel = new QuinielasModel();
        $this->leaguesModel = new LeaguesModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = service('session');

        $data['title'] = 'Inicio';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'logout'];
        $data['js'] = 'home/home.js';
        $data['menuResult'] = $session->get('menu');

        $data['user'] = $this->userModel->getUserData();
        $data['upcomingFixtures'] = $this->getUpcomingFixtures();
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Home/index', $data);
        echo view('Templates/footer', $data);
    }

    function getUpcomingFixtures()
    {
        $league['ids'] = $this->leaguesModel->getQuinielaLeagues();

        $filtersFixtures['date'] = "";
        $fixtures = $this->leaguesModel->getBdUpcomingFixtures($league);
        
        $partidos_ids = array();
        foreach ($fixtures as $keyFixture => $fixture) 
        {
            $fixtures[$keyFixture]['prediction_home'] = "";
            $fixtures[$keyFixture]['prediction_away'] = "";
            $fixtures[$keyFixture]['partido_id'] = "";
            $fixtures[$keyFixture]['partido_id_db'] = "";
            $fixtures[$keyFixture]['pronostico_id'] = "";

            array_push($partidos_ids,$fixture['id']);
        }

        $pronostico['usuario_id'] = getUserSession();
        $pronostico['partidos_ids'] = $partidos_ids;
        $pronosticoPartidos = $this->quinielasModel->getPartidos($pronostico);
        foreach ($pronosticoPartidos->getResult() as $keyPronostico => $pronostico) 
        {
            $fixtures[$pronostico->partido]['prediction_home'] = $pronostico->pronostico_local ;
            $fixtures[$pronostico->partido]['prediction_away'] = $pronostico->pronostico_visitante;
            $fixtures[$pronostico->partido]['partido_id'] = util_encode($pronostico->partido);
            $fixtures[$pronostico->partido]['partido_id_db'] = util_encode($pronostico->partido_id);
            $fixtures[$pronostico->partido]['pronostico_id'] = util_encode($pronostico->pronostico_id);
        }

        $data['fixtures'] = util_arraySort($fixtures, 'date', SORT_ASC);

        return view('Matches/matchesList', $data);;
    }
}
