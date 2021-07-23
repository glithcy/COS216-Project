<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type ="text/css" href="./css/trending.css">
    <script src="./js/jquery-3.5.0.js"></script>
  <script src="./js/trending.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-file=cover">
  <title>FLOW</title>
  <link rel = "icon" href ="./img/pinkCircle.png" type = "image/x-icon">
    <?php include './php/logout.php';?>
</head>

<body>
<?php include './php/header.php';?>
<script>
    document.getElementById("6").className = "active";
</script>

<main style="display: none" >
  <div class="row">

    <div id="submit">


        <input type="search" id="searchin">
        <button id="submitSearch" onclick="search()"><img id = "submitIcon" src="img/searchicon.png" alt="search"/></button>
        <button id = showAll onclick="showAll()">Show All</button>

        <select id="year">
          <option id = "yearName" value="ALL" class = "year" >All Years</option>
          <option value="2020" class="year">2020</option>
          <option value="2019" class="year">2019</option>
          <option value="2018" class="year">2018</option>
          <option value="BEFORE" class="year">Before</option>
        </select>

        <select id="genre">
          <option id = "All Genre" value="ALL">All Genres</option>
          <option value="POP" >Pop</option>
          <option value="HIP HOP">Rap/Hip Hop</option>
          <option value="JAZZ">Jazz</option>
          <option value="OTHER">Other</option>
        </select>

        <button id = "setFilter" onClick="setFilters()">Set Filters</button>

        <button id="set" onClick="settings()">Save Filters</button>

        </div>

  </div>

  <div class="row">
        <!-- empty-->
  </div>


</main>
<div id = "cover" >
  <img src="./img/loader.gif" style="border:none; box-shadow:0px 0px 0px; margin-top: 30vh" alt="loading">
</div>
<?php include './php/footer.php';?>
</body>
</html>