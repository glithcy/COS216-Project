<?php
require_once "config.php";

class Connection
{
    private static $instance = null;
    private $connect;

    private function __construct()
    {
        try {
            $this->connect = new PDO('mysql:host=wheatley.cs.up.ac.za;dbname=u19014938_COS216_DB','u19014938','blueshoe');
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public static function getInstance()
    {
        if(self::$instance==null)
            self::$instance = new Connection();
        return self::$instance;
    }

    public function getConnection()
    {
        if($this->connect instanceof PDO){
            return $this->connect;
        }
    }
}