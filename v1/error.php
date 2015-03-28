<?php

class Error {

    function __construct($message, $code) {
        
        if($code == 200||$code == 201||$code == 302||$code == 204||$code == 205){
            $errarr = array('response' => array('message' => $message, 'code' => $code));
        }
        else{
            $errarr = array('error' => array('message' => $message, 'code' => $code));
        }
        
        $settings = new ApiSettings();
        $format = $settings->getFileType();



        if ($format == '.json') {
            header('Content-Type: application/json');
            $error = json_encode($errarr);
        }
        if ($format == '.xml') {
            header('Content-Type: application/xml');
            if($code == 200||$code == 201||$code == 204||$code == 205||$code == 302){
                $error = "<response>";
            }
            else{
                $error = "<error>";
            }
            $error.="<message>" . $message . "</message>";
            $error.="<code>" . $code . "</code>";
            if($code == 200){
                $error .= "</response>";
            }
            else{
                $error.="</error>";
            }
        }
        exit($error);
    }

}

?>