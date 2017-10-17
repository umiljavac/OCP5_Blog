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
    protected $varsNames;
    protected $vars = [];

    public function __construct($url, $view, array $varsNames)
    {
        $this->setUrl($url);
        $this->setView($view);
        $this->setVarsNames($varsNames);
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
        return !empty($this->varsNames());
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

    public function setVarsNames( array $varsNames)
    {
        $this->varsNames = $varsNames;
    }

    public function setVars( array $vars)
    {
        $this->vars = $vars;
    }

    public function view()
    {
        return $this->view;
    }

    public function varsNames()
    {
        return $this->varsNames;
    }

    public function vars()
    {
        return $this->vars;
    }

}