<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type ="text/css" href="./css/track.css">
    <meta charset="UTF-8">
    <script src="./js/jquery-3.5.0.js"></script>
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script src="./socket/track.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FLOW</title>
    <link rel = "icon" href ="./img/pinkCircle.png" type = "image/x-icon">
    <?php include './php/logout.php';?>
</head>
<body>
<?php include './php/header.php';?>
<script>
    document.getElementById("2").className = "active";
</script>

<main>
    <div id="au">
        <audio controls id="audio" name="smallsong" preload="metadata"> <source src="./music/songsmall.mp3" type="audio/mp3"></audio>
    </div>
    <div id="message"></div>
</main>
<?php include './php/footer.php';?>


</body>


</html>