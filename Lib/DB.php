<?php
/* @File :  to set the db configuration */
namespace Weblab\Lib;

require_once("config.php");

class DB
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        // building data source name from config
        $dsn = 'mysql:host=' . DB_SERVER .
               ';dbname='    . DB_DATABASE .
               ';port='      . DB_PORT .
               ';connect_timeout=15';
        // getting DB user from config                
        $user = DB_USERNAME;
        // getting DB password from config                
        $password = DB_PASSWORD;

        $this->dbh = new \PDO($dsn, $user, $password);
    }

    /* only static method available in class to get the SINGLETON object of the db */
    public static function getInstance()
    {
        // set if already object set - return if exist - singleton - or new one
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    public function __clone() {
        return false;
    }

    // others global functions
}


?>
