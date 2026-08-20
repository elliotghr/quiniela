<?php

namespace App\Controllers\Configuration;

use App\Controllers\BaseController;
use App\Models\RolsModel;
use App\Models\ModuleModel;

class AccessController extends BaseController
{
    function __construct()
	{
        $this->rolsModel = new RolsModel();
        $this->moduleModel = new ModuleModel();
	}

    public function index()
    {
        $session = service('session');
        
        /* GENERALES */
        $data['title'] = 'Configuración de Accesos';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'logout'];
        $data['js'] = 'config/access.js';
        $data['menuResult'] = $session->get('menu');
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        /* CATALOGOS */
        $data['rols'] = $this->rolsModel->getRols();

        /* MODALES */

        /* DATA */

        /* VISTAS */
        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Configuration/Access/index', $data);
        echo view('Templates/footer', $data);
    }

    public function getAccess()
    {
        $id = $this->request->getPost('rol') !== null ? trim($this->request->getPost('rol')) : null;

        if(isset($id) && $id != "")
        {
            $access = $this->moduleModel->getModulesByRolId($id);
            $modules = $this->moduleModel->getModules();

            $data['accesos'] = $access->getResultArray();
            $data['modulos'] = $modules->getResultArray();
            $data['dataTable'] = view('Configuration/Access/dataTable', $data);
            
            $data['status'] = 'OK';
            $data['message'] = 'Accesos obtenidos con éxito';
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }

        return json_encode($data);
    }

    public function saveAccess()
    {
        $data['status'] = 'OK';
        $data['message'] = 'Permisos guardados con éxito';

        $accesos = $this->request->getPost('access') !== null ? $this->request->getPost('access') : null;
        $rolId = $this->request->getPost('rol') !== null ? $this->request->getPost('rol') : null;

        $accessArray = [];
        if(isset($accesos) && $accesos != ""
            && isset($rolId) && $rolId != "")
        {
            foreach($accesos as $id => $acceso)
            {
                $access = [];
                if(isset($acceso['access']))
                {
                    $access['rol_id'] = $rolId;
                    $access['modulo_id'] = $id;
                    $access['escritura'] = isset($acceso['write']) ? true : false;
                    $accessArray[] = $access;
                }
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Algo salió mal';
        }

        $this->moduleModel->saveAccess($rolId, $accessArray);
        // $data['debug'] = $accessArray;

        return json_encode($data);
    }
    
}
