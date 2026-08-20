<?php

namespace App\Libraries;
use Exception;

class MailChimp
{
	public function sendTo($subject, $message, $to, $attach = null, $from = 'default', $bcc = null)
	{
        /********************************************** EMAIL FROM *********************************************/

        $from = $this->from($from);

        /*************************************** EMAIL STRUCTURE MESSAGE ***************************************/
        
        $mailApe['to'] = $this->to($to, $bcc);
        $mailApe['subject'] = $subject ? $subject : 'ANEMEX' ;
        $mailApe['from_email'] = $from;
        $mailApe['html'] = $message ? $message : '' ;
                
        /*************************************** ATTACHMENTS (OPTIONAL) ****************************************/
        
        if($attach) {
            $attachments = $this->addAttachment($attach);

            if (count($attachments) > 0) {
                $mailApe['attachments'] = $attachments;
            }
        }

        /********************************************** SEND EMAIL *********************************************/
        
        $resultMonkey = $this->sendmonkey($mailApe);
        
        /****************************************** DELETE ATTACHMENTS *****************************************/        

        if($attach) {
            $this->removeAttachment($attach);
        }

        return $resultMonkey;
	}

    /**
     * @param string attachPath | ruta absoluta del archivo a enviar
     */
    public function sendTemplate($templateName, $subject, $to, $mergeVars, $attachPath = null, $from = 'default', $bcc = null, $templateContent = null)
    {

        $mailchimp = new \MailchimpTransactional\ApiClient();
        
        $key = env('mailchimp.enviroment');
        $key = env("mailchimp.$key.key");        
        $mailchimp->setApiKey($key);
        
        $fromEmail = $this->from($from);
        $fromName = $this->fromName($from);
        $message['to'] = $this->to($to, $bcc);
        $message['subject'] = $subject ? $subject : 'ANEMEX' ;
        $message['from_email'] = $fromEmail;
        $message['from_name'] = $fromName;
        $message['global_merge_vars'] = $mergeVars;
        
        if($templateContent == null) {

            $templateContent = [
                [
                    'name' => 'main',
                    'content' => 'Texto de relleno, esto es obligatorio.'
                ]
            ];
        }

        if($attachPath)
        {
            $attachments = $this->addAttachment($attachPath);

            if (count($attachments) > 0)
            {
                $message['attachments'] = $attachments;
            }
        }
        
        $response = $mailchimp->messages->sendTemplate([
            "template_name" => $templateName, 
            "template_content" => $templateContent, 
            "message" => $message
            ]
        );

        return $response;
    }

    private function from($from)
    {
        return env("mailchimp.$from.email", "no-reply@anemex.com.mx");;
    }

    private function fromName($from)
    {
        return env("mailchimp.$from.fromName", "ANEMEX");
    }

    private function to($to, $bcc = null)
    {
        //$to && $cc it's the same process, both individually count by one email 

        $recipients = [];

        if ($to) {
            $to = str_replace(' ', '', trim($to));
            $to = explode(',', $to);

            foreach ($to as $email) {
                $recipients[] = [
                    "email" => $email,
                    "type"  => 'to',
                ];
            }
        }

        if ($bcc) {
            $bcc = str_replace(' ', '', trim($bcc));
            $bcc = explode(',', $bcc);

            foreach ($bcc as $email)
            {
                $recipients[] = [
                    "email" => $email,
                    "type"  => 'bcc',
                ];
            }
        }

        return $recipients;
    }

    private function addAttachment($attach = null)
    {
        $sendAttach = [];

        if(!is_array($attach))
        {
            if (file_exists($attach))
            {
                $sendAttach = [[
                    "name" => pathinfo($attach, PATHINFO_BASENAME),
                    "type"    => $this->mimeType($attach),
                    "content" => base64_encode(file_get_contents($attach))
                ]];
                // unlink($attach);
    
                return $sendAttach;                
            }

        }

        foreach($attach as $attachFile)
        {
            if (file_exists($attachFile))
            {
                $sendAttach[] =[
                    "name" => pathinfo($attachFile, PATHINFO_BASENAME),
                    "type"    => $this->mimeType($attachFile),
                    "content" => base64_encode(file_get_contents($attachFile))
                ];
                // unlink($attachFile);                
            }
        }
        
        return $sendAttach;
    }

    private function mimeType($filePath)
    {

        if (!file_exists($filePath)) {
            throw new Exception('No existe el archivo.');
        }

        $file = new \CodeIgniter\Files\File($filePath);
        $mimeType = null;

        $mimeType = $file->getMimeType();

        return $mimeType;
    }

    private function removeAttachment($attach = null)
    {
        if(!is_array($attach)) {
            if(file_exists($attach)) {
                // unlink($attach);
            }
            return;
        }

        foreach ($attach as $file) {
            if (file_exists($file)) {
                // unlink($file);
            }
        }
    }

    private function sendmonkey($message)
    {
        try {
            $mailchimp = new \MailchimpTransactional\ApiClient();
            
            $key = env('mailchimp.enviroment');
            $key = env("mailchimp.$key.key"); 
            $mailchimp->setApiKey($key);

            $mailchimp->messages->send(["message" => $message]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
