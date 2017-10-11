<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 26/09/2017
 * Time: 10:15
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