<?php

class Token {

    public static alert = false;
    public static email = '';

    // Create Custom Key
    public static function token_creator()
    {
        $string = 'QWERTYUIOP[]ASDFGHJKLZXCVBNMqwertyujpasdfghjklzxcvbnm1234567890/*-+@!#$%^&*()';
        $max = strlen($string);
        $random = '';
        for($i=0;$i<$max;$i++){
            $random .= $string[mt_rand(0,$max)];
        }
        return $random;
    }

    // Add Token  Session
    public static function token_add($key)
    {
        $_SESSION[$_SERVER['QUERY_STRING'].'_'.$key] = Token::token_creator();
        if(!empty($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]) && isset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]))
            return $token;
        else
            return false;
    }

    // Alert Hack Ip and Date To Admin
    public static function token_alert()
    {
        if(Token::alert === true){
            $message = 'CSRF Attack Founded ! - date '.date("d/m/Y G:i:s - e");
            $to      = Token::email;
            $subject = "CSRF Token";
            $from    = "info@domain.com";
            $headers  = "From: " . $from . "\r\n";
            $headers .= "Reply-To: ". $from . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            mail($to, $subject, $message, $headers);
        }
    }

    // View Token Input
    public static function token_view($key)
    {
        $token = Token::token_add($key);
        if($token)
            return print '<input type="hidden" value="'.base64_encode($token).'" name="token'.'_'.$key.'">';
        else
            return false;
    }

    // Return Token String
    public static function token_return()
    {
        $token = Token::token_add($key);
        if($token)
            return base64_encode($token);
        else
            return false;
    }


    // Validation Token
    public static function token_validation($key)
    {
        if(!isset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]))
            $status = false;
        else if(!isset($_POST['token'.'_'.$key]))
            unset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]);
            $status = false;
        else if(base64_decode($_POST['token'.'_'.$key]) != $$_SESSION[$_SERVER['QUERY_STRING'].'_'.$key])
            unset($_SESSION[$_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]);
            $status = false;
        else
            unset($_SESSION[$_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]);
            $status = true;

        if($status === true)
            return true;
        else
            Token::alert();
            return false;

    }

}
