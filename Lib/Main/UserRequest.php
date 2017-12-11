<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 07/12/2017
 * Time: 16:03
 */

/**
 * This class allow to access the variables and request method contained in the user request.
 */

namespace Main;

class UserRequest
{
    public function requestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function postData($key)
    {
        return isset($_POST[$key])? $_POST[$key] : null;
    }

    public function getData($key)
    {
        return isset($_GET[$key])? $_GET[$key] : null;
    }
}