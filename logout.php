<?php
  require "php/db_connection.php";

  if($con) {
    $query = "UPDATE admin_credentials SET IS_LOGGED_IN = 'false'";
    $result = mysqli_query($con, $query);
  }
  ?>

  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>Logout</title>
      <script src="js/restrict.js"></script>
    </head>
    <body>
      <script type="text/javascript">
        session_start();
      window.location.href = "http://localhost/Pharmacy-Management/index.html";
      // komentar
      </script>
    </body>
    <script>
      
    </script>
  </html>

