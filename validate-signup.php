<?php
session_start();
//might nt need to be inluded if its in the header.php file
include './php/config.php'; //needs to be included

//print_r( $_POST );

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$fname = test_input($_POST['fname']);
$lname = test_input($_POST['lname']);
$email = test_input($_POST['email']);
$pass = $_POST['psw'];

//validate on server side (to-do)

$valid = true;
if (!preg_match("/^[a-zA-Z\s]*$/", $fname))
{
    $valid = false;
}

if(!preg_match("/^[a-zA-Z\s]*$/", $lname))
{
    $valid = false;
}

if(!preg_match("/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email))
{
    $valid = false;
}

if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,20})/", $pass))
{
    $valid = false;
}

if($valid)
{
    $API = "";
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $API = substr(str_shuffle($permitted_chars), 0, 10);
    //the number of characters is less than the total set of characters shuffled
    //doesnt matter if you cant get the same character twice


    //ADD SALT TO PASSWORD
    //LAST THREE CHARACTER OF NAME ADDED TO FRONT ORDINAL VALUE + 3, +2, +1
    $salt = "-45dfeHK/__yu349@-/klF21-1_\/4JkUP/4";
    $salt .= chr(ord(substr($pass, 0,1)) +3);
    $salt .= chr(ord(substr($pass, 1,1)) +3);
    $salt .= chr(ord(substr($pass, 2,1)) +3);
    $pass .= $salt; //salt is added to end
//    $password .= $pass;
    $emailvalid = true;



    //hash the password
    //https://jonsuh.com/blog/securely-hash-passwords-with-php/

    // The value of $password_hash
    // should similar to the following:
    // $2y$10$aHhnT035EnQGbWAd8PfEROs7PJTHmr6rmzE2SvCQWOygSpGwX2rtW
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    try {
        //https://thisinterestsme.com/pdo-insert-example/
        $sql = "INSERT INTO `Users` (`Name`, `Surname`, `Password`, `API_key`, `Email`, `Theme`, `Genre`, `Year`) VALUES (:fname, :lname, :pass, :API, :email, :theme, :genre, :year_)";

        $statement = $pdo->prepare($sql);

        $statement->bindValue(':fname', $fname);
        $statement->bindValue(':lname', $lname);
        $statement->bindValue(':pass', $password_hash);
        $statement->bindValue(':API', $API);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':theme', "dark");
        $statement->bindValue(':genre', "All Genres");
        $statement->bindValue(':year_', "All Years");

        // $inserted = $statement->execute(array(":fname"=>$fname,));
        $inserted = $statement->execute();

        //close connection
        $pdo = null;

        //return to original page
        $_SESSION['api'] = $API;
        //$_SESSION['password'] = $password_hash;

        header("Location: ./signup.php");
        exit;

    }
    catch(PDOException $e)
    {
        //THE ERROR OCCURS AND IT HOPEFULLY JUST RESETS THE ORIGINAL PAGE WITH EMPTY VALUES
        //EMAIL SHOULD BE EMPTY
        $emailvalid = false;
        $_SESSION['email'] = $emailvalid;
        header("Location: ./signup.php");
    }



}else
{
    $_SESSION['api'] = "invalid";
}

//randomly generated API key to show to user
