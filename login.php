<?php
//session_start();
//?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type ="text/css" href="./css/login.css">
    <script src="./js/jquery-3.5.0.js"></script>
    <script src="./js/login.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-file=cover">
    <title>FLOW</title>
    <link rel = "icon" href ="./img/pinkCircle.png" type = "image/x-icon">
    <?php include './php/logout.php';?>
</head>
<body>
<?php include './php/header.php';?>
<main>


    <div id="container">
        <form action="#" name="login" onsubmit="callback();return false">
            <div class="container">
                <h1>Login to Flow Account</h1>

                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Enter Email" id="email" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" maxlength="20" placeholder="Enter Password" id="psw" required>

                <br/>
                <input type="submit" value="Login" class="signupbtn" id="signupbtn">
            </div>

        </form>
        <div id="out"></div>
    </div>
</main>
<?php include './php/footer.php';?>

<?php
//// remove all session variables
//session_unset();

//// destroy the session
//session_destroy();
?>
</body>
</html>
