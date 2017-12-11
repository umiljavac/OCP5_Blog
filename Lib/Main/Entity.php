<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 23:30
 */

/**
 *Abstract class Entity implements ArrayAccess interface
 * used as parent class of BlogPost, Image, Comment classes.
 * The constructor need an array of data and use it's hydrate function to set the properties of the instance of the inherited class
 */

namespace Main;


abstract class Entity implements \ArrayAccess
{
    protected $id;
    protected $erreurs = [];

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function hydrate($data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set'. ucfirst($key);

            if (is_callable([$this, $method]))
            {
                $this->$method($value);
            }
        }
    }

    public function isNew()
    {
        return empty($this->id);
    }

    /***********************************************
                        SETTERS
     ***********************************************/

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /***********************************************
                        GETTERS
     ***********************************************/

    public function id()
    {
        return $this->id;
    }

    public function erreurs()
    {
        return $this->erreurs;
    }

   public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var]))
        {
            return $this->$var();
        }
    }

    public function offsetSet($var, $value)
    {
        $method = 'set'.ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method]))
        {
            $this->$method($value);
        }
    }

    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset($var)
    {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }

}

