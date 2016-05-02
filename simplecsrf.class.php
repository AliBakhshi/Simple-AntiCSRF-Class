<?php

class Token {
    public static $alert_status = false;
    public static $email = '';
    // Create Custom Key
    public static function token_creator()
    {
        $string = 'QWERTYUIOP[]ASDFGHJKLZXCVBNMqwertyujpasdfghjklzxcvbnm1234567890/*-+@!#$%^&*()';
        $max = strlen($string) - 1;
        $string = str_split($string);
        $random = '';
        for($i=0;$i<$max;$i++){
            $min = rand(0,$max);
            $random .= $string[rand($min,rand($min,$max))];
        }
        return $random;
    }
    // Add Token  Session
    public static function token_add($key)
    {
        $_SESSION[$_SERVER['QUERY_STRING'].'_'.$key] = Token::token_creator();
        if(!empty($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]) && isset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key])) :
            return $_SESSION[$_SERVER['QUERY_STRING'].'_'.$key];
        else :
            return false;
        endif;
    }
    // Alert Hack Ip and Date To Admin
    public static function token_alert()
    {
        if(Token::$alert_status === true){
            $message = 'CSRF Attack Founded ! - date '.date("d/m/Y G:i:s - e");
            $to      = Token::$email;
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
        if($token) :
            return print '<input type="hidden" value="'.md5($token).'" name="token'.'_'.$key.'">';
        else :
            return false;
        endif;
    }
    // Return Token String
    public static function token_return()
    {
        $token = Token::token_add($key);
        if($token) :
            return md5($token);
        else :
            return false;
        endif;
    }
    // Validation Token
    public static function token_validation($key)
    {
        if(!isset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]))
        {
            $status = false;
        }
        else if(!isset($_POST['token'.'_'.$key]))
        {
            unset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]);
            $status = false;
        }
        else if($_POST['token'.'_'.$key] != md5($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]))
        {
            unset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]);
            $status = false;
        }
        else 
        {
            unset($_SESSION[$_SERVER['QUERY_STRING'].'_'.$key]);
            $status = true;
        }
              
        if($status === true)
        {
            return true;
        }   
        else 
        {
            Token::token_alert();
            return false;
        }
            
    }
}
