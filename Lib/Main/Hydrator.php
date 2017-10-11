<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 10:41
 */
namespace Main;

trait Hydrator
{
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

}