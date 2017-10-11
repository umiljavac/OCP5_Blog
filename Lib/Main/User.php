<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 05/10/2017
 * Time: 11:35
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
      //  unset($_SESSION['message']);
        return $message;
    }

}