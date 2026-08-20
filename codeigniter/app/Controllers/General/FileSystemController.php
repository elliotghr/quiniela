<?php

namespace App\Controllers\General;

use App\Controllers\BaseController;
use App\Models\UserModel;

class FileSystemController extends BaseController
{
    function __construct()
	{
        $this->userModel = new UserModel();
	}

    public function uploadAvatar()
    {
        helper('filesystem');

        $data['status'] = 'OK';
        $data['message'] = 'Imagen actualizada con éxito';

        $validationRule = [
            'image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[fileAvatar]'
                    . '|is_image[fileAvatar]'
                    . '|mime_in[fileAvatar,image/jpg,image/png,image/jpeg]'
                    . '|max_size[fileAvatar,1000]'
                    . '|max_dims[fileAvatar,5120,5120]',
                'errors' => [
                    'uploaded' => '* Formato de imagen incorrecto',
                    'is_image' => '* Formato de imagen incorrecto',
                    'mime_in' => '* Formato de imagen incorrecto',
                    'max_size' => '* El tamaño de la imagen debe de ser de 1MB como máximo',
                    'max_dims' => '* Las dimensiones de la imagen deben de ser de 5120 x 5120 como máximo',
                ]
            ],
        ];

        if (!$this->validate($validationRule)) {
            $data['status'] = 'ERROR';
            $data['message'] = '';

            if($this->validator->hasError('image'))
            {
                $data['message'] = $this->validator->getError('image');
            }

            return json_encode($data);
        }

        $img = $this->request->getFile('fileAvatar');

        if (!$img->hasMoved())
        {
            $newName = $img->getRandomName();
            $img->move(WRITEPATH . 'avatar', $newName);

            $user = $this->userModel->getUserData();
            $userRow = $user->getRowArray();

            if($userRow['avatar'] != 'default.png')
            {
                unlink(WRITEPATH . 'avatar/' . $userRow['avatar']);
            }

            $dataUser['usuario_id'] = getUserSession();
            $dataUser['avatar'] = $newName;
            $this->userModel->saveDataUser($dataUser);
        }
        else
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'El archivo se ha movido';
        }

        return json_encode($data);
    }  
}
