<?php

namespace App\Controllers\Configuration;

use App\Controllers\BaseController;
use App\Models\RolsModel;

class RolsController extends BaseController
{
    function __construct()
	{
        $this->rolModel = new RolsModel();
	}

    public function index()
    {
        $session = service('session');
        
        /* GENERALES */
        $data['title'] = 'Configuración de Roles';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'logout', 'validate', 'modal'];
        $data['js'] = 'config/rols.js';
        $data['menuResult'] = $session->get('menu');
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        /* CATALOGOS */

        /* MODALES */
        $data['modalDelete'] = view('Templates/modalDelete', $data);

        $data['formEdit'] = '';
        $data['modalEdit'] = view('Templates/modalEdit', $data);

        $data['formNew'] = view('Configuration/Rols/formNew', $data);
        $data['modalNew'] = view('Templates/modalNew', $data);

        /* DATA */
        $data['dataTable'] = $this->dataTable();

        /* VISTAS */
        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Configuration/Rols/index', $data);
        echo view('Templates/footer', $data);
    }
    
    public function newRol()
    {
        $rol['descripcion'] = $this->request->getPost('descripcion') !== null ? trim($this->request->getPost('descripcion')) : null;

        if(isset($rol['descripcion']) && $rol['descripcion'] != "")
        {
            $this->rolModel->newRol($rol);
            $data['dataTable'] = $this->dataTable();
            $data['status'] = 'OK';
            $data['message'] = 'Rol agregado con éxito';
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Debes llenar los campos obligatorios';
        }
        return json_encode($data);
    }

    public function deleteRol()
    {
        $rol['id'] = $this->request->getPost('id') !== null ? trim($this->request->getPost('id')) : null;

        if(isset($rol['id']) && $rol['id'] != "")
        {
            $this->rolModel->deleteRol($rol);
            $data['dataTable'] = $this->dataTable();
            $data['status'] = 'OK';
            $data['message'] = 'Rol eliminado con éxito';
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }
        return json_encode($data);
    }

    public function saveRol()
    {
        $rol['id'] = $this->request->getPost('rolId') !== null ? trim($this->request->getPost('rolId')) : null;
        $rol['descripcion'] = $this->request->getPost('descripcion') !== null ? trim($this->request->getPost('descripcion')) : null;

        if(isset($rol['id']) && $rol['id'] != "" &&
            isset($rol['descripcion']) && $rol['descripcion'] != "")
        {
            $this->rolModel->saveRol($rol);
            $data['dataTable'] = $this->dataTable();
            $data['status'] = 'OK';
            $data['message'] = 'Rol guardado con éxito';
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }
        return json_encode($data);
    }

    public function getRol()
    {
        $id = $this->request->getPost('id') !== null ? trim($this->request->getPost('id')) : null;

        if(isset($id) && $id != "")
        {
            $request = $this->rolModel->getRolById($id);

            if($request->getNumRows() == 1)
            {
                $data['rol'] = $request->getRowArray();
                $data['formEdit'] = view('Configuration/Rols/formEdit', $data);
                
                $data['status'] = 'OK';
                $data['message'] = 'Usuario obtenido con éxito';
            }
            else
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'Rol no encontrado';
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
        $data['rols'] = $this->rolModel->getRols();
        $data['dataTable'] = view('Configuration/Rols/dataTable', $data);

        return $data['dataTable'];
    }
}
