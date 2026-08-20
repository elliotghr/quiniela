<?php

function util_arraySort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function util_encode($text)
{
    /* Esta utilidad está diseñada para codificar texto que se necesiten utilizar en el frontend o url, ya que la utilidad de encrypt devuelve bites ilegibles en texto plano */
    $encrypter = service('encrypter');
    return bin2hex($encrypter->encrypt((string)$text));
}

function util_decode($text)
{
    /* Esta utilidad está diseñada para decodificar texto que se necesiten utilizar en el frontend o url, ya que la utilidad de encrypt devuelve bites ilegibles en texto plano */
    $encrypter = service('encrypter');
    $response = false;
    
    try
    {
        $response = $encrypter->decrypt(hex2bin($text));
    }
    catch (\Throwable $th)
    {
        return $response;
    }

    return $response;
}

function util_reCaptcha()
{
    $captcha = true; 
    
    if(env('reCaptchaV2.active') === true)
        $captcha = util_reCaptchaV2();
    elseif(env('reCaptchaV3.active') === true)
        $captcha = util_reCaptchaV3();

    return $captcha;
}

function util_reCaptchaV2()
{
    $request = service('request');
    $reCaptchaV2 = false;

    if(env('reCaptchaV2.active') === true)
    {
        $response = $request->getPost('g-recaptcha-response') !== null ? $request->getPost('g-recaptcha-response') : null;
        $captchaResponse = file_get_contents(env('reCaptchaV2.api') . "?secret=" . env('reCaptchaV2.secret') . "&response=" . $response);

        $captchaArray = json_decode($captchaResponse, true);

        $reCaptchaV2 = $captchaArray['success'] ? true : false;
    }
    else
    {
        $reCaptchaV2 = true;
    }

    return $reCaptchaV2;
}

function util_reCaptchaV3()
{
    $request = service('request');
    $reCaptchaV3 = false;

    if(env('reCaptchaV3.active') === true)
    {
        $response = $request->getPost('token') !== null ? $request->getPost('token') : null;
        $action = $request->getPost('action') !== null ? $request->getPost('action') : null;
        $captchaResponse = file_get_contents(env('reCaptchaV3.api') . "?secret=" . env('reCaptchaV3.secret') . "&response=" . $response);

        $captchaArray = json_decode($captchaResponse, true);

        $reCaptchaV3 = ($captchaArray['success'] === true && $captchaArray['action'] === $action && $captchaArray['score'] >= 0.5) ? true : false;
    }
    else
    {
        $reCaptchaV3 = true;
    }

    return $reCaptchaV3;
}
