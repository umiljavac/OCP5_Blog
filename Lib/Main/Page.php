<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 15:00
 */

/**
 * Class Page
 * The $fileView property will contain the view that the User wants to see.
 * The $vars will contain variables needed to complete the view (ex : the content of a blogpost)
 */

namespace Main;


class Page
{
    protected $fileView;
    protected $vars = [];

    /***********************************************
                        SETTERS
     ***********************************************/

    public function setFileView($fileView)
    {
        if(!is_string($fileView) || empty($fileView))
        {
            throw new \InvalidArgumentException('la vue spécifiée n\'existe pas ou est invalide');
        }
        $this->fileView = $fileView;
    }

    public function addVars(array $vars)
    {
        foreach ($vars as $var => $value)
        {
            if (!is_string($var) || is_numeric($var) || empty($var))
            {
                throw new \InvalidArgumentException('le nom de la variable doit être une chaîne de caractère non nulle');
            }
        }
        $this->vars = $vars;
    }

    /***********************************************
                        GETTERS
     ***********************************************/

    public function fileView()
    {
        return $this->fileView;
    }

    public function vars()
    {
        return $this->vars;
    }
}