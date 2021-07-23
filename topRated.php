<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type ="text/css" href="./css/topRated.css">
    <script src="./js/jquery-3.5.0.js"></script>
    <script src="./js/topRated.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-file=cover">
    <title>FLOW</title>
    <link rel = "icon" href ="./img/pinkCircle.png" type = "image/x-icon">
    <?php include './php/logout.php';?>
</head>

<body>
<?php include './php/header.php';?>
<script>
    document.getElementById("4").className = "active";
</script>

<main style="display:none">
    <div class="row">

    </div>
</main>
<div id = "cover" >
    <img src="./img/loader.gif" style="border:none; box-shadow:0px 0px 0px; margin-top: 30vh" alt="loading">
</div>
<?php include './php/footer.php';?>
</body>


</html>