<?php
//global variables
$servername = "wheatley.cs.up.ac.za";
$username = "u19014938";
$password = "blueshoe";
$dbname = "u19014938_COS216_DB";
////make global to call quesries on it later
global $pdo;

//define('DBUSER',"19014938");
//define('DBPWD',"blueshoe");
//define('DBHOST',"wheatley.cs.up.ac.za");
//define('DBNAME',"u19014938_COS216_DB");

//echo DBHOST;

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   //echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

//You still need to close the connection but I dont know where
//I dont want to close it immediately
//close after you've
//$conn = null;
//object oriented manner
