<?php
session_start();

include './php/config.php';

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$email = test_input($_POST['email']);
$pass = $_POST['psw'];

        $salt = "-45dfeHK/__yu349@-/klF21-1_\/4JkUP/4";
        $salt .= chr(ord(substr($pass, 0,1)) +3);
        $salt .= chr(ord(substr($pass, 1,1)) +3);
        $salt .= chr(ord(substr($pass, 2,1)) +3);
        $pass .= $salt; //salt is added to end


    try {

        $stmt = $pdo->prepare("SELECT * FROM `Users` WHERE `Email`=?");

        $stmt->execute([$email]); //email passed in

        if($user = $stmt->fetch()){ //retrieved something (exists in db)
            $hash = $user['Password'];
            echo "<br/>";
            //echo "from db: " . $user['Password'];
            echo "<br/>";

            if (password_verify($pass, $hash)) {
                //echo 'Password is valid!';
                $_SESSION['api'] = $user['API_key'];
                $_SESSION['name'] = $user['Name'] ." ". $user['Surname'];
            } else {
                //echo 'Invalid password.';
                $_SESSION['password'] = false;
            }
        }else{
            //doesnt exist in db
            $_SESSION['email'] = false;
        }

        //close connection
        $pdo = null;

        header("Location: ./login.php");
        exit;

    }
    catch(PDOException $e)
    {
        //THE ERROR OCCURS AND IT HOPEFULLY JUST RESETS THE ORIGINAL PAGE WITH EMPTY VALUES
        //EMAIL SHOULD BE EMPTY
//        $emailvalid = false;
//        $_SESSION['email'] = $emailvalid;
//        echo "invalid";
       // header("Location: ./login.php");
    }


