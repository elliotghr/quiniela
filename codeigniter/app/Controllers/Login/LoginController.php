<?php

namespace App\Controllers\Login;

use App\Controllers\BaseController;
use App\Models\UserModel;
use DateTime;
use App\Libraries\MailChimp;

class LoginController extends BaseController
{
    function __construct()
	{
        $this->userModel = new UserModel();
	}

    public function index()
    {
        $data['title'] = 'Quinieland';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'validate', 'modal', 'reCaptchaV3'];
        $data['js'] = 'login/login.js';
        $data['bodyClass'] = 'loginBackground';
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        /* MODALES */
        $data['formResetPassword'] = view('Login/formResetPassword', $data);
        $data['modalResetPassword'] = view('Templates/modalResetPassword', $data);

        $data['formNew'] = view('Login/formNew', $data);
        $data['modalNew'] = view('Templates/modalNew', $data);

        /* VISTAS */
        echo view('Templates/header', $data);
        echo view('Login/index', $data);
        echo view('Templates/footer', $data);
    }

    public function auth()
    {
        $session = service('session');

        $usr = $this->request->getPost('user') !== null ? $this->request->getPost('user') : null;
        $pwd = $this->request->getPost('password') !== null ? $this->request->getPost('password') : null;

        $captcha = util_reCaptcha();

        if($captcha)
        {
            setUserSession($usr, $pwd);
            
            if($session->has('usuario'))
            {
                $data['status'] = 'OK';
                $data['message'] = 'Credenciales correctas';
            }
            else
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'Usuario y/o contraseña no válido(s)';
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Por favor verifica que no eres un robot';
        }

        return json_encode($data);
    }

    public function logout()
    {
        unsetSession();
    }

    public function resetPassword()
    {
        $usr = $this->request->getPost('correo') !== null ? $this->request->getPost('correo') : null;

        $data['status'] = 'OK';
        $data['message'] = 'Se te ha enviado un correo electrónico para continuar con el proceso de cambio de contraseña.';

        if(isset($usr))
        {
            $user = $this->userModel->getUser($usr);

            if($user->getNumRows() == 1)
            {
                $userRow = $user->getRowArray();
                $id = $userRow['id'];
                $cambio_clave = rand(1000,9999);
                $password = md5(($id * $cambio_clave) + $id + $cambio_clave);
                $code = hashPassword($password);
                $code = base64_encode($code);

                $usuario['id'] = $id;
                $usuario['cambio_clave'] = $cambio_clave;
                $usuario['fecha_cambio_clave'] = date('Y-m-d H:i:s');

                $this->userModel->saveUser($usuario);

                $url = base_url('/login/verifyReset?a=' . $id . "&b=" . $cambio_clave . "&c=" . $code);

                $mailChimp = new MailChimp();

                $templateVars =
                [
                    [
                        'name' => 'USERNAME',
                        'content' => $userRow['nombre']
                    ],
                    [
                        'name' => 'URLPASS',
                        'content' => $url
                    ]
                ];

                $emailResponse = $mailChimp->sendTemplate(
                        templateName: 'quinielandRecuperarClave',
                        subject: 'Solicitud de Cambio de Contraseña',
                        to: $userRow['usuario'],
                        mergeVars: $templateVars);
            }
            else
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'El correo que proporcionaste no se encuentra registrado.';
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'El correo es obligatorio.';
        }

        return json_encode($data);
    }

    public function verifyReset()
    {
        $id = $this->request->getGet('a') !== null ? $this->request->getGet('a') : null;
        $cambio_clave = $this->request->getGet('b') !== null ? $this->request->getGet('b') : null;
        $code = $this->request->getGet('c') !== null ? $this->request->getGet('c') : null;

        if(isset($id) && isset($cambio_clave) && isset($code))
        {
            $code = base64_decode($code);
            $password = md5(($id * $cambio_clave) + $id + $cambio_clave);

            $user = $this->userModel->getUserDataById($id);

            if($user->getNumRows() == 1)
            {
                $userRow = $user->getRowArray();

                $fecha_cambio_clave = new DateTime($userRow['fecha_cambio_clave']);
                $diff = $fecha_cambio_clave->diff(new DateTime());

                $totalMin = $diff->y * 12 * 30 * 24 * 60;
                $totalMin += $diff->m * 30 * 24 * 60;
                $totalMin += $diff->d * 24 * 60;
                $totalMin += $diff->h * 60;
                $totalMin += $diff->i;

                if($totalMin <= 60)
                {
                    if($userRow['cambio_clave'] > 0)
                    {
                        if($cambio_clave === $userRow['cambio_clave'])
                        {
                            if(hashVerifyPassword($password, $code))
                            {
                                $data['status'] = 'OK';
                                $data['message'] = '';
                                $data['newPass'] = generateRandomPassword();

                                $usuario['id'] = $id;
                                $usuario['cambio_clave'] = 0;
                                $usuario['primera_vez'] = 1;
                                $usuario['clave'] = hashPassword($data['newPass']);

                                $this->userModel->saveUser($usuario);
                            }
                            else
                            {
                                // EL HASH DE LA URL ES INCORRECTO
                                $data['status'] = 'ERROR';
                                $data['message'] = 'URL Incorrecta';
                            }
                        }
                        else
                        {
                            // EL CAMPO CAMBIO CLAVE DE LA BASE NO COINCIDE CON LA URL
                            $data['status'] = 'ERROR';
                            $data['message'] = 'URL Incorrecta';
                        }
                    }
                    else
                    {
                        $data['status'] = 'ERROR';
                        $data['message'] = 'La URL ya ha sido utilizada, si aún quieres reestablecer tu contraseña solicita un nuevo cambio.';
                    }
                }
                else
                {
                    $data['status'] = 'ERROR';
                    $data['message'] = 'La URL ya ha excedido 1 hora, si aún quieres reestablecer tu contraseña solicita un nuevo cambio.';
                }
            }
            else
            {
                // USUARIO NO ENCONTRADO
                $data['status'] = 'ERROR';
                $data['message'] = 'URL Incorrecta';
            }
        }
        else
        {
            // FALTAN PARAMETROS
            $data['status'] = 'ERROR';
            $data['message'] = 'URL Incorrecta';
        }

        $data['title'] = 'Login';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'globalCSS', 'fontawesome'];
        echo view('Templates/header', $data);

        if($data['status'] == 'ERROR')
        {
            echo view('Templates/errorPage', $data);
        }
        else
        {
            echo view('Login/changePassword', $data);
        }

        echo view('Templates/footer', $data);
    }
}
