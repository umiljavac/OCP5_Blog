<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 17/10/2017
 * Time: 09:01
 */

namespace Main;


class Config
{
    protected $vars = [];

    public function __construct()
    {
        $xml = new \DOMDocument();
        $xml->load(__DIR__.'/../../Config/preferences.xml');
        $elements = $xml->getElementsByTagName('pagination');

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