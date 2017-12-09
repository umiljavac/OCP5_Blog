<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 17/10/2017
 * Time: 09:01
 */

/**
 * Class Config parse the preferences.xml file.
 * The variables are stocked in attribute $vars and define the number of blogposts or comments for each page.
 * In concerned views, like the index of all blogposts, the getConfig() method is used to make a pagination.
 */

namespace Main;


class Config
{
    protected $vars = [];

    public function __construct($fileLoaded, $elementTagName)
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