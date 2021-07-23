<?php
require_once "Connection.php";

class Validator
{
    public static function validate($key)
    {
        try {
            $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Users WHERE API_key=?");
            $query->execute([$key]);
            if($query->fetch(PDO::FETCH_ASSOC))
                return true;
        }
        catch (PDOException $exception) {
            echo $exception->getMessage();
        }
        return false;
    }
}