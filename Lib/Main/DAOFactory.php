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
    private $config;

    public function __construct()
    {
        $this->config = new Config(__DIR__ . '/../../Config/dbConnection.xml', 'connection');
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
            $errorDB = fopen(__DIR__ .'/../../Errors/errorDB.txt', 'a+');
            fputs($errorDB, date(DATE_RSS) . ' : ' . $e->getMessage() . PHP_EOL);
            fclose($errorDB);
            $_SESSION['error'] = 'errorDB';
            header('Location: /Errors/errorDB.html');
        }
    }
}