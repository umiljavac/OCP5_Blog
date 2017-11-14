<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 10:15
 */

/**
 * Class DAOFactory create an instance of PDO and the connexion with the data base.
 * change the parameter 'dsn', 'username' and 'password' according to your own configuration.
 */
namespace Main;


class DAOFactory
{
    public static function getMysqlConnectionWithPDO()
    {
        try {
            $db = new \PDO('mysql:host=localhost;dbname=OCP5_blog', 'root', 'root');
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