<!DOCTYPE html>
<html lang="en">
  <head>
    <link href="./css/calendar.css" rel="stylesheet">
    <script src="./js/jquery-3.5.0.js"></script>
    <script src="./js/calendar.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-file=cover">
    <title>FLOW</title>
    <link rel = "icon" href ="./img/pinkCircle.png" type = "image/x-icon">
      <?php include './php/logout.php';?>
  </head>
  <body>

  <?php include './php/header.php';?>
  <script>
      document.getElementById("1").className = "active";
  </script>

  <main style="display:none;">
    <div id="page-body">
      <!-- [PERIOD SELECTOR] -->
      <div id="cal-date">
        <select id="cal-mth"></select>
        <select id="cal-yr"></select>
        <input id="cal-set" type="button" value="SET"/>

        <input id="cal-prev" type="button" value="PREV"/>
        <input id="cal-next" type="button" value="NEXT"/>
        <input id="cal-today" type="button" value="TODAY"/>
      </div>

      <!-- [CALENDAR] -->
      <div id="cal-container"></div>

      <!-- [EVENT] -->
      <div id="cal-event"></div>
    </div>
  </main>
  <div id = "cover" >
    <img src="./img/loader.gif" style="border:none; box-shadow:0px 0px 0px; margin-top: 30vh" alt="loading">
  </div>
  <?php include './php/footer.php';?>
  </body>
</html>