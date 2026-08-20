<?php

namespace App\Controllers\Participants;

use App\Controllers\BaseController;
use App\Models\QuinielasModel;
use App\Models\UserModel;
use App\Libraries\MailChimp;

class ParticipantsController extends BaseController
{
    function __construct()
	{
        $this->quinielasModel = new QuinielasModel();
        $this->userModel = new UserModel();
	}

    public function index()
    {
        /* GENERALES */
        $data['title'] = 'Agregar Participante';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'validate', 'modal', 'reCaptchaV3'];
        $data['js'] = 'participants/participants.js';
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        /* DATA */
        $urlToken = $this->request->getGet('qid') !== null ? trim($this->request->getGet('qid')) : null;
        $data['errorId'] = true;
        $data['quiniela_id'] = null;

        if($urlToken)
        {
            $result = $this->quinielasModel->getQuinielaByUrlEncode(['url_encode' => $urlToken]);

            if($result->getNumRows() > 0)
            {
                $quinielaRow = $result->getRowArray();
                $data['quiniela_id'] = util_encode($quinielaRow['quiniela_id']);
                $data['max_pronosticos'] = $quinielaRow['max_pronosticos'];
                $data['errorId'] = false;
            }
        }

        /* VISTAS */
        $data['main'] = view('Participants/formNew', $data);
        echo view('Templates/header', $data);
        echo view('Participants/index', $data);
        echo view('Templates/footer', $data);
    }

    public function saveParticipant()
    {
        $data['status'] = "OK";
        $data['message'] = "Participante agregado con éxito";

        $pronostico['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;
        $auxiliar['pronosticos'] = $this->request->getPost('pronosticos') !== null ? trim(util_decode($this->request->getPost('pronosticos'))) : null;
        $datosUsuario['nombre'] = $this->request->getPost('nombre') !== null ? $this->request->getPost('nombre') : null;
        $datosUsuario['apellido_paterno'] = $this->request->getPost('apellidoPaterno') !== null ? $this->request->getPost('apellidoPaterno') : null;
        $datosUsuario['apellido_materno'] = $this->request->getPost('apellidoMaterno') !== null ? $this->request->getPost('apellidoMaterno') : null;
        $usuario['usuario'] = $this->request->getPost('correo') !== null ? $this->request->getPost('correo') : null;
        $data['correo'] = $usuario['usuario'];
        $captcha = util_reCaptchaV2();

        if ($datosUsuario['nombre'] === null or $usuario['usuario'] === null or $auxiliar['pronosticos'] === null)
        {
            $data['status'] = "ERROR";
            $data['message'] = "Favor de llenar todos los campos";
        }
        elseif(!$captcha)
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Por favor verifica que no eres un robot';
        }elseif(!$pronostico['quiniela_id'])
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }

        if($data['status'] !== 'ERROR')
        {
            $user = $this->userModel->getUser($usuario['usuario']);
            if($user->getNumRows() > 1)
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'Algo salió mal';
            }
            elseif($user->getNumRows() === 1)
            {
                if(!$this->savePart($user, $pronostico, $auxiliar))
                {
                    $data['status'] = 'ERROR';
                    $data['message'] = 'Ya te has suscrito a la quiniela con anterioridad';
                }
                else
                {
                    $data['next'] = view('Participants/resultJoined', $data);
                }
            }
            else
            {
                $clave = generateRandomPassword();
                $usuario['clave'] = hashPassword($clave);
                $usuario['rol_id'] = '2';
                $this->userModel->newUser($usuario, $datosUsuario);

                $user = $this->userModel->getUser($usuario['usuario']);

                if(!$this->savePart($user, $pronostico, $auxiliar))
                {
                    $data['status'] = 'ERROR';
                    $data['message'] = 'Ya te has suscrito a la quiniela con anterioridad';
                }
                else
                {
                    $mailChimp = new MailChimp();

                    $mergeVars =
                    [
                        [
                            'name' => 'USERNAME',
                            'content' => $datosUsuario['nombre']
                        ],
                        [
                            'name' => 'USERUSER',
                            'content' => $usuario['usuario']
                        ],
                        [
                            'name' => 'USERPASS',
                            'content' => $clave
                        ],
                        [
                            'name' => 'QUINIELAND',
                            'content' => base_url()
                        ]
                    ];

                    $data['emailResponse'] = $mailChimp->sendTemplate(
                        templateName: 'quinielandNuevoUsuario',
                        subject: 'Bienvenido a Quinieland',
                        to: $usuario['usuario'],
                        mergeVars: $mergeVars);

                    $data['next'] = view('Participants/resultNew', $data);
                }
            }
        }

        return json_encode($data);
    }

    private function savePart($user, $pronostico, $auxiliar)
    {
        $userRow = $user->getRowArray();
        $pronostico['usuario_id'] = $userRow['id'];

        $quiniela = $this->quinielasModel->getPronosticos($pronostico);

        if($quiniela->getNumRows() > 0)
        {
            return false;
        }
        else
        {
            $pronosticos = array();
            for($cont = 1; $cont <= $auxiliar['pronosticos']; $cont++)
            {
                $pronostico['consecutivo'] = $cont;
                $pronosticos[] = $pronostico;
            }

            $this->quinielasModel->newPronosticos($pronosticos);
        }

        return true;
    }
}
