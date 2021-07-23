<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type ="text/css" href="./css/signup.css">
    <script src="./js/signup.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-file=cover">
    <title>FLOW</title>
    <link rel = "icon" href ="./img/pinkCircle.png" type = "image/x-icon">
</head>


<body>
<?php include './php/header.php';?>
<?php // print_r($_SESSION["API"]);?>
<script>
    document.getElementById("0").className = "active";
</script>
<main>
    <!--    <img src="./img/Flow.png" alt="logo" style="width:15%; margin-top:30vh; ">-->
    <div id="container">
        <form name="register" method = "post" action="validate-signup.php" onsubmit="return validate()">
            <div class="container">
                <h1>Login</h1>
                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Enter Email" name="email" required>
                <label for="psw"><b>Password</b></label>
                <input type="password" maxlength="20" placeholder="Enter Password" name="psw" required>
                <input type="submit" value="Login" class="signupbtn" id="signupbtn">
            </div>
            <?php
            if(isset($_SESSION['api']))
            {
                echo "<p style='color: #62ff00; font-size: 50%' id ='api'>Your API key is ";
                echo $_SESSION['api'];
                echo "</p>";
                unset($_SESSION['api']);
            }
            if(isset($_SESSION['password']))
            {
                echo "<p style='color: #ff0015; font-size: 50%' id ='passerror'>Your password hash is ";
                echo $_SESSION['password'];
                echo "</p>";
                unset($_SESSION['password']);
            }
            if(isset($_SESSION['email']))
            {
                echo "<p style='color: #ff0015; font-size: 50%' id ='error'>Email already exists.</p>";
                unset($_SESSION['email']);
            }
            ?>
        </form>
    </div>
</main>
<?php include './php/footer.php';?>

<?php
//// remove all session variables
//session_unset();
//
//// destroy the session
//session_destroy();
//?>
</body>


</html>

