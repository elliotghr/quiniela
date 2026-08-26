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
        $league['ids'] = $this->leaguesModel->getQuinielaLeagues();
        $league['season'] = env('quiniela.season');
        $league['rounds'] = explode('|', 'Group Stage - 1|Group Stage - 2|Group Stage - 3');
        $data['fixtures'] = $this->leaguesModel->getMultipleFixtures($league);
        $data['fixtures'] = util_arraySort($data['fixtures'], 'date', SORT_ASC);

        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Home/index', $data);
        echo view('Templates/footer', $data);
    }
}
