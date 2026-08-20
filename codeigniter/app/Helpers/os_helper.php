<?php

function os_loadImage($image)
{
    try
    {
        $path = WRITEPATH . $image;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    catch(Exception $e)
    {
        try
        {
            $image= "avatar/default.png";
            $path = WRITEPATH . $image;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        catch(Exception $e)
        {
            $base64 = "";
        }
    }

    return $base64;
}