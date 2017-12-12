<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 17/10/2017
 * Time: 09:01
 */

/**
 * Class Config parse the preferences.xml and dbConnection.xml files.
 * The variables are stocked in attribute $vars and allow to configure the application with getConfig() method.
 */

namespace Main;


class Config
{
    protected $vars = [];

    public function parseFile($fileLoaded, $elementTagName)
    {
        $xml = new \DOMDocument();
        $xml->load($fileLoaded);
        $elements = $xml->getElementsByTagName($elementTagName);
        foreach ($elements as $element)
        {
            $this->vars[$element->getAttribute('var')] = $element->getAttribute('value');
        }
    }

    public function getConfig($var)
    {
        if (isset($this->vars[$var]))
        {
            return $this->vars[$var];
        }
        return null;
    }
}
