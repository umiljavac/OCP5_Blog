<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 05/10/2017
 * Time: 11:35
 */

/**
 * Class User is used to create and return session variables and set cookies if needed.
 */

namespace Main;

session_start();

class User
{
    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr])? $_SESSION[$attr] : null;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function hasMessage()
    {
        return isset($_SESSION['message']);
    }

    public function setMessage($value)
    {
        $_SESSION['message'] = $value;
    }

    public function getMessage()
    {
        $message = $_SESSION['message'];
        return $message;
    }

    public function setCookie($name, $value ='', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

}