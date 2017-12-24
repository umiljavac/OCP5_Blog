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

    public function getLabelsFromString($var)
    {
        $label = $this->getConfig($var);
        $labels = explode(',', $label);

        $values = $this->convertLabels($label);
        $mixTab = [];
        $i = 0;
        foreach ($values as $value )
        {
            $mixTab[$value] = $labels[$i];
            $i++;
        }
        return $mixTab;
    }

    public function convertLabels($label)
    {
        $value = $this->stringToLowerNoAccent($label);
        $values = explode(',', $value);
        foreach ($values as $key => $value)
        {
            $values[$key] = str_replace(' ','-', $value);
        }
        return $values;
    }

    public function stringToLowerNoAccent($str)
    {
        $url = $str;
        $url = preg_replace('#Ç#', 'C', $url);
        $url = preg_replace('#ç#', 'c', $url);
        $url = preg_replace('#è|é|ê|ë#', 'e', $url);
        $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
        $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
        $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
        $url = preg_replace('#ì|í|î|ï#', 'i', $url);
        $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
        $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
        $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
        $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
        $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
        $url = preg_replace('#ý|ÿ#', 'y', $url);
        $url = preg_replace('#Ý#', 'Y', $url);

        $url = strtolower($url);
        return ($url);
    }
}
