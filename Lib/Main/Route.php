<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 13:13
 */
namespace Main;

class Route
{
    protected $url;
    protected $view;
    protected $varName;
    protected $varValue;

    public function __construct($url, $view, $varName)
    {
        $this->setUrl($url);
        $this->setView($view);
        $this->setVarName($varName);
    }

    public function matchUrl($url)
    {

        if (preg_match('#^'.$this->url.'$#', $url, $matches))
        {
            return $matches;
        }
        else
        {
            return false;
        }
    }

    public function hasVar()
    {
        if (!empty($this->varName()))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function setUrl($url)
    {
        if(is_string($url))
        {
            $this->url = $url;
        }
    }

    public function setView($view)
    {
        if(is_string($view) && !empty($view))
        {
            $this->view = $view;
        }
    }

    public function setVarName($varName)
    {
        if(is_string($varName))
        {
            $this->varName = $varName;
        }
    }

    public function setVarValue($varValue)
    {
        $this->varValue = $varValue;
    }

    public function view()
    {
        return $this->view;
    }

    public function varName()
    {
        return $this->varName;
    }

    public function varValue()
    {
        return $this->varValue;
    }

}