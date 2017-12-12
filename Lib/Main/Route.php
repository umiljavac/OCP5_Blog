<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 13:13
 */

/**
 * Class Route
 * An instantiation of this class allows to stock all informations of a route contained in the routes.xml file.
 * The constructor set the $url, $view and $varnames attributes.
 * The method matchUrl() is used to compare the $url attribute with the $_SERVER['REQUEST_URI'].
 * If variables are contained in the url The method matchUrl() also return the value of the variables.
 *
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

    public function hasVars()
    {
        return !empty($this->varsNames());
    }

    /***********************************************
                        SETTERS
     ***********************************************/

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

    /***********************************************
                        GETTERS
     ***********************************************/

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
