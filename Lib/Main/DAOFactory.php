<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 10:15
 */

/**
 * Class DAOFactory create an instance of PDO and the connexion with the data base.
 * You can modify your connection settings in Config/dbConnection.xml file.
 */
namespace Main;


class DAOFactory
{
    protected $config;

    public function __construct()
    {
        $this->config = new Config();
        $this->config->parseFile(__DIR__ . '/../../Config/dbConnection.xml','connection');
    }

    public function getMysqlConnectionWithPDO()
    {
        try {
            $db = new \PDO('mysql:host=' . $this->config->getConfig('host') . ';
                                     dbname=' . $this->config->getConfig('dbname') ,
                                    $this->config->getConfig('username'),
                                    $this->config->getConfig('password'));
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $db;
        }
        catch (\PDOException $e)
        {
           $this->config->writeError($e);
           header('Location: /Errors/errorDB.html');
        }
    }
}
