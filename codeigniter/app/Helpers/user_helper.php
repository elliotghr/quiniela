<?php

use App\Models\UserModel;

function validateChangePassword()
{
    $userModel = new UserModel();
    $userID = getUserSession();
    $cambio = false;

    $result = $userModel->getUserDataById($userID);
    $user = $result->getRowArray();

    if($user['primera_vez'] == 1)
    {
        $cambio = true;
    }

    return $cambio;
}

function generateRandomPassword()
{
    return substr(base64_encode(md5((strtotime(date('Y-m-d H:i:s'))))), 0, 10);
}

function hashPassword($pwd)
{
    $newPwd = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
    
    return $newPwd;
}

function hashVerifyPassword($pwd, $hash)
{
    if($pwd === true) // SOLICITO UN CAMBIO DE PASSWORD
        return true;
    else
        return password_verify($pwd, $hash);
}

function verifyUser($usr, $pwd)
{
    $userModel = new UserModel();
    $userRow = false;

    if(isset($usr) && isset($pwd))
    {
        $query = $userModel->getUser($usr);

        if($query->getNumRows() == 1)
        {
            $userRow = $query->getRowArray();
            if(!hashVerifyPassword($pwd, $userRow['clave']))
            {
                $userRow = false;
            }
        }
    }
    
    return $userRow;
}

function setUserSession($usr, $pwd)
{
    $session = service('session');
    $encrypter = service('encrypter');

    $userRow = verifyUser($usr, $pwd);
    
    if($userRow !== false)
    {
        $session->set('usuario', $encrypter->encrypt($userRow['id']));
    }
    else
    {
        if($session->has('usuario'))
        {
            $session->destroy();
        }
    }
}

function getUserSession()
{
    $session = service('session');
    $encrypter = service('encrypter');
    $id = 0;
    
    if($session->has('usuario'))
    {
        $id = $encrypter->decrypt($session->get('usuario'));
    }

    return $id;
}

function unsetSession()
{
    $session = service('session');
    
    if($session->has('usuario'))
    {
        $session->destroy();
    }
}

function setMenuSession()
{
    $session = service('session');
    $userModel = new UserModel();

    $query = $userModel->getMenu();
    $result = $query->getResultArray();

    $session->set('menu', $result);
}

function validateAccess()
{
    $session = service('session');
    $router = service('router');
    $valid = false;

    $menuResult = $session->get('menu');

    foreach($menuResult as $menu)
    {
        if(str_replace('/(.*)', '', $router->getMatchedRoute()[0]) == $menu['url'])
        {
            $valid = true;
            break;
        }
    }

    return $valid;
}

