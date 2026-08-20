<?php

namespace App\Controllers\Configuration;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AccountController extends BaseController
{
    public function index()
    {
        $session = service('session');
        $userModel = new UserModel();
        
        $data['title'] = 'Mi Cuenta';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'logout', 'validate', 'upload'];
        $data['js'] = 'config/account.js';
        $data['menuResult'] = $session->get('menu');
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        $data['user'] = $userModel->getUserData();
        $data['changePassword'] = validateChangePassword();

        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Configuration/Account/index', $data);
        echo view('Templates/footer', $data);
    }
    
    public function savePassword()
    {
        $session = service('session');
        $userModel = new UserModel();

        $changePassword = validateChangePassword();

        $oldPass = $this->request->getPost('oldPassword') !== null ? $this->request->getPost('oldPassword') : ($changePassword ? true : null);
        $newPass = $this->request->getPost('newPassword') !== null ? $this->request->getPost('newPassword') : null;

        if(isset($oldPass) && isset($newPass))
        {
            if($oldPass !== $newPass)
            {
                $query = $userModel->getCurrentUser();

                if($query->getNumRows() == 1)
                {
                    $userRow = $query->getRowArray();
                    if(hashVerifyPassword($oldPass, $userRow['clave']))
                    {
                        $userModel->savePassword($userRow['id'], $newPass);
                        $data['status'] = 'OK';
                        $data['message'] = 'Contraseña modificada con éxito';
                        $data['changePassword'] = $changePassword ? '1' : '0';
                    }
                    else
                    {
                        $data['status'] = 'ERROR';
                        $data['message'] = 'Contraseña anterior incorrecta';
                    }
                }
                else
                {
                    $data['status'] = 'ERROR';
                    $data['message'] = 'Usuario duplicado';
                }
            }
            else
            {
                $data['status'] = 'ERROR';
                $data['message'] = 'La contraseña nueva es igual a la anterior';
            }
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Debes llenar los campos obligatorios';
        }

        return json_encode($data);
    }
}
