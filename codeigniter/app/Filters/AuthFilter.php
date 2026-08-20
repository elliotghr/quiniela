<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');
        $encrypter = service('encrypter');
		$router = service('router');

		$class  = $router->controllerName();
		$class = explode("\\", $class);
		$class = array_pop($class);
		$method = $router->methodName();
		$params = array_filter($_POST);

        if(in_array($class, ['LoginController']))
        {
            if($session->has('usuario') && $method != 'logout')
            {
                return redirect()->to(base_url('home'));
            }
        }
        elseif(in_array($class, explode('|', env('openControllers'))))
        {
            return;
        }
        else
        {
            if($session->has('usuario'))
            {
                helper('user');
                setMenuSession();
                $valid = validateAccess();
                $changePassword = validateChangePassword();

                if(!$valid)
                {
                    if(in_array($class, explode('|', env('fullAccessControllers'))))
                    {
                        return;
                    }
                    else
                    {
                        return redirect()->to(base_url('home'));
                    }
                }

                if($changePassword && !(in_array($class, ['AccountController'])))
                {
                    return redirect()->to(base_url('config/account'));
                }
            }
            else
            {
                return redirect()->to(base_url('login'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
		$router = service('router');

		$class  = $router->controllerName();
		$class = explode("\\", $class);
		$class = array_pop($class);
		$method = $router->methodName();
		$params = array_filter($_POST);

        if($class == 'LoginController' && $method == 'logout')
        {
            return redirect()->to(base_url('login'));
        }
    }
}
