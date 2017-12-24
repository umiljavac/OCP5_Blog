<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 10:20
 */

/**
 * Abstract class Manager
 * Parent class of all managers.
 * The constructor call the static function getMysqlConnectionWithPDO() of DAOFactory class
 */

namespace Main;


abstract class Manager
{
    protected $db;
    protected $entity;

    public function __construct()
    {
        $daoFactory = new DAOFactory();
        $this->db = $daoFactory->getMysqlConnectionWithPDO();
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set' . ucfirst($key);
            if (is_callable([$this->entity, $method]))
            {
                $this->entity->$method($value);
            }
        }
    }

    public function entity()
    {
        return $this->entity;
    }
}
