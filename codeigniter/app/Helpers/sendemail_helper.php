<?php

use App\Libraries\mailChimp;

function sendemail($settings)
{
    $mailchimp = new mailChimp();

    return $mailchimp->sendTo($settings['subject'], $settings['message'], $settings['to'], $settings['attach'], $settings['from'], $settings['bcc']);
}

function sendemailNative($settings)
{
    $email = service('email');

    $config['protocol'] = 'smtp';
    $config['SMTPHost'] = 'mail.hackmaly.com';
    $config['SMTPUser'] = 'no-reply@hackmaly.com';
    $config['SMTPPass'] = 'hd3Wfes19+haH=EYTW';
    $config['SMTPPort'] = '587'; // 25 - 465 - 587
    $config['SMTPCrypto'] = 'tls'; // tls - ssl
    $config['SMTPTimeout'] = '5';
    $config['SMTPKeepAlive'] = 'false';
    $config['charset']  = 'iso-8859-1';
    $config['wordWrap'] = true;
    $config['mailType'] = "html";
    $config['newline'] = "\r\n";

    $email->initialize($config);

    $email->setFrom('no-reply@hackmaly.com', 'hackmaly.com');
    $email->setTo($settings['to']);
    $email->setSubject($settings['subject']);
    $email->setMessage($settings['message']);

    if(!$email->send(false))
    {
        return $email->printDebugger();
    }

    return true;
}
