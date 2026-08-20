<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('', ['filter' => 'auth'], static function ($routes) 
{
    $routes->group('/', ['namespace' => 'App\Controllers\Login'], static function ($routes)
    {
        $routes->get('', 'LoginController::index');
        $routes->get('logout', 'LoginController::logout');

        $routes->group('login', function ($routes)
        {
            $routes->get('', 'LoginController::index');
            $routes->get('verifyReset', 'LoginController::verifyReset');
            $routes->post('(:any)', 'LoginController::$1');
        });
        
    });

    $routes->group('/home', ['namespace' => 'App\Controllers\Home'], static function ($routes)
    {
        $routes->get('', 'HomeController::index');
    });

    $routes->group('/fs', ['namespace' => 'App\Controllers\General'], static function ($routes)
    {
        $routes->post('uploadAvatar', 'FileSystemController::uploadAvatar');
    });

    $routes->group('/config', ['namespace' => 'App\Controllers\Configuration'], static function ($routes)
    {
        $routes->group('rols', function ($routes) {
            $routes->get('', 'RolsController::index');
            $routes->post('(:any)', 'RolsController::$1');
        });

        $routes->group('account', function ($routes) {
            $routes->get('', 'AccountController::index');
            $routes->post('(:any)', 'AccountController::$1');
        });

        $routes->group('users', function ($routes) {
            $routes->get('', 'UsersController::index');
            $routes->post('(:any)', 'UsersController::$1');
        });

        $routes->group('access', function ($routes) {
            $routes->get('', 'AccessController::index');
            $routes->post('(:any)', 'AccessController::$1');
        });
    });

    $routes->group('/quinielas', ['namespace' => 'App\Controllers\Quinielas'], static function ($routes)
    {
        $routes->group('mis-quinielas', function ($routes) {
            $routes->get('', 'MisQuinielasController::index');
            $routes->post('(:any)', 'MisQuinielasController::$1');
        });
    });

    $routes->group('/participant', ['namespace' => 'App\Controllers\Participants'], static function ($routes)
    {
        $routes->get('add', 'ParticipantsController::index');
        $routes->post('(:any)', 'ParticipantsController::$1');
    });
});