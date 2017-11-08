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

    public function __construct()
    {
        $this->db = DAOFactory::getMysqlConnectionWithPDO();
    }
}