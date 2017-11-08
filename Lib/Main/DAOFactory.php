<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 10:15
 */

/**
 * Class DAOFactory create an instance of PDO and the connexion with the data base.
 * change the parameter 'dsn', 'username' and 'passwd' according to your own configuration.
 */
namespace Main;


class DAOFactory
{
    public static function getMysqlConnectionWithPDO()
    {
        $db = new \PDO('mysql:host=localhost;dbname=OCP5_blog', 'root','root');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}