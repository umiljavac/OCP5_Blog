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
    private $connectionVars = [];

    public function __construct()
    {
        $xml = new \DOMDocument();
        $xml->load(__DIR__ . '/../../Config/dbConnection.xml');

        $elements = $xml->getElementsByTagName('connection');

        foreach ( $elements as $element)
        {
            $this->connectionVars[$element->getAttribute('var')] = $element->getAttribute('value');
        }
    }

    public function getMysqlConnectionWithPDO()
    {
        try {
            $db = new \PDO('mysql:host=' . $this->connectionVars['host'] . ';
                                     dbname=' . $this->connectionVars['dbname'] ,
                                    $this->connectionVars['username'],
                                    $this->connectionVars['password'] );
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $db;
        }
        catch (\Exception $e)
        {
            $errorDB = fopen(__DIR__ .'/../../Errors/errorDB.txt', 'a+');
            fputs($errorDB, date(DATE_RSS) . ' : ' . $e->getMessage() . PHP_EOL);
            fclose($errorDB);
            $_SESSION['error'] = 'errorDB';
            header('Location: /Errors/errorDB.html');
        }
    }
}