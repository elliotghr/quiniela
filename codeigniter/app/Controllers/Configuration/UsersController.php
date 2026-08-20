<?php

namespace App\Controllers\Configuration;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RolsModel;

class UsersController extends BaseController
{
    function __construct()
	{
        $this->userModel = new UserModel();
        $this->rolsModel = new RolsModel();
	}

    public function index()
    {
        $session = service('session');
        
        /* GENERALES */
        $data['title'] = 'Configuración de Usuarios';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'logout', 'validate', 'modal'];
        $data['js'] = 'config/users.js';
        $data['menuResult'] = $session->get('menu');
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        /* CATALOGOS */
        $data['rols'] = $this->rolsModel->getRols();

        /* MODALES */
        $data['modalDelete'] = view('Templates/modalDelete', $data);

        $data['formEdit'] = '';
        $data['modalEdit'] = view('Templates/modalEdit', $data);

        $data['formNew'] = view('Configuration/Users/formNew', $data);
        $data['modalNew'] = view('Templates/modalNew', $data);

        /* DATA */
        $data['dataTable'] = $this->dataTable();

        /* VISTAS */
        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Configuration/Users/index', $data);
        echo view('Templates/footer', $data);
    }
    
    public function newUser()
    {
        $user['rol_id'] = $this->request->getPost('rol') !== null ? trim($this->request->getPost('rol')) : null;
        $user['usuario'] = $this->request->getPost('correo') !== null ? trim($this->request->getPost('correo')) : null;
        $user['clave'] = isset($user['usuario']) ? hashPassword(substr($user['usuario'], 0, strpos($user['usuario'], "@"))) : null;
        $dataUser['nombre'] = $this->request->getPost('nombre') !== null ? trim($this->request->getPost('nombre')) : null;
        $dataUser['apellido_paterno'] = $this->request->getPost('apellidoPaterno') !== null ? trim($this->request->getPost('apellidoPaterno')) : null;
        $dataUser['apellido_materno'] = $this->request->getPost('apellidoMaterno') !== null ? trim($this->request->getPost('apellidoMaterno')) : null;
        $dataUser['fecha_nacimiento'] = $this->request->getPost('fechaNacimiento') !== null ? trim($this->request->getPost('fechaNacimiento')) : null;

        $data['status'] = 'OK';
        $data['message'] = 'Usuario agregado con éxito';

        if(isset($user['rol_id']) && $user['rol_id'] != ""
            && isset($dataUser['nombre']) && $dataUser['nombre'] != ""
            && isset($user['usuario']) && $user['usuario'] != ""
            && isset($user['clave']) && $user['clave'] != "")
        {
            if(isset($dataUser['fechaNacimiento']) && $dataUser['fechaNacimiento'] != "")
            {
                $pattern = "/[0-9]{4}-[0-9]{2}-[0-9]{2}/i";
                if(!preg_match($pattern, $dataUser['fechaNacimiento']))
                {
                    $data['status'] = 'ERROR';
                    $data['message'] = 'Formatos incorrectos en los campos';
                }
            }

            $pattern = "/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/i";
            if(!preg_match($pattern, $user['usuario']))
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'Formatos incorrectos en los campos';
            }

            if($data['status'] == 'OK')
            {
                $this->userModel->newUser($user, $dataUser);
                $data['dataTable'] = $this->dataTable();
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Debes llenar los campos obligatorios';
        }
       
        return json_encode($data);
    }

    public function deleteUser()
    {
        $usuario['usuario_id'] = $this->request->getPost('id') !== null ? trim($this->request->getPost('id')) : null;

        if(isset($usuario['usuario_id']) && $usuario['usuario_id'] != "")
        {
            $this->userModel->deleteUser($usuario);
            $data['dataTable'] = $this->dataTable();
            $data['status'] = 'OK';
            $data['message'] = 'Usuario eliminado con éxito';
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }
        return json_encode($data);
    }

    public function saveUser()
    {
        $user['id'] = $this->request->getPost('usuarioId') !== null ? trim($this->request->getPost('usuarioId')) : null;
        $user['rol_id'] = $this->request->getPost('rol') !== null ? trim($this->request->getPost('rol')) : null;
        $user['usuario'] = $this->request->getPost('correo') !== null ? trim($this->request->getPost('correo')) : null;
        // $user['clave'] = isset($user['usuario']) ? hashPassword(substr($user['usuario'], 0, strpos($user['usuario'], "@"))) : null;
        $dataUser['id'] = $this->request->getPost('datosUsuarioId') !== null ? trim($this->request->getPost('datosUsuarioId')) : null;
        $dataUser['nombre'] = $this->request->getPost('nombre') !== null ? trim($this->request->getPost('nombre')) : null;
        $dataUser['apellido_paterno'] = $this->request->getPost('apellidoPaterno') !== null ? trim($this->request->getPost('apellidoPaterno')) : null;
        $dataUser['apellido_materno'] = $this->request->getPost('apellidoMaterno') !== null ? trim($this->request->getPost('apellidoMaterno')) : null;
        $dataUser['fecha_nacimiento'] = $this->request->getPost('fechaNacimiento') !== null ? trim($this->request->getPost('fechaNacimiento')) : null;

        $data['status'] = 'OK';
        $data['message'] = 'Usuario modificado con éxito';

        if(isset($user['rol_id']) && $user['rol_id'] != ""
            && isset($dataUser['nombre']) && $dataUser['nombre'] != ""
            && isset($user['usuario']) && $user['usuario'] != "")
            // && isset($user['clave']) && $user['clave'] != "")
        {
            if(isset($dataUser['fechaNacimiento']) && $dataUser['fechaNacimiento'] != "")
            {
                $pattern = "/[0-9]{4}-[0-9]{2}-[0-9]{2}/i";
                if(!preg_match($pattern, $dataUser['fechaNacimiento']))
                {
                    $data['status'] = 'ERROR';
                    $data['message'] = 'Formatos incorrectos en los campos';
                }
            }

            $pattern = "/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/i";
            if(!preg_match($pattern, $user['usuario']))
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'Formatos incorrectos en los campos';
            }

            if($data['status'] == 'OK')
            {
                $this->userModel->saveUser($user, $dataUser);
                $data['dataTable'] = $this->dataTable();
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Debes llenar los campos obligatorios';
        }
       
        return json_encode($data);
    }

    public function getUser()
    {
        $id = $this->request->getPost('id') !== null ? trim($this->request->getPost('id')) : null;

        if(isset($id) && $id != "")
        {
            $request = $this->userModel->getUserDataById($id);

            if($request->getNumRows() == 1)
            {
                $data['usuario'] = $request->getRowArray();
                $data['rols'] = $this->rolsModel->getRols();
                $data['formEdit'] = view('Configuration/Users/formEdit', $data);
                
                $data['status'] = 'OK';
                $data['message'] = 'Usuario obtenido con éxito';
            }
            else
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'Usuario no encontrado';
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }

        return json_encode($data);
    }

    private function dataTable()
    {
        $data['users'] = $this->userModel->getUsers();
        $data['dataTable'] = view('Configuration/Users/dataTable', $data);

        return $data['dataTable'];
    }
    
}
