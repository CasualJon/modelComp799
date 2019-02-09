<?php
  session_start();
  $_SESSION['admin'] = 0;
  require './php_includes/db.php';

  echo "<p>Hey, we made it here :D</p>";

?>
