<?php
  session_start();
  echo "<p>".$_SESSION['message']."</p>";

  if (isset($_SESSION['debug'])) echo "<p>".$_SESSION['debug']."</p>";
  
?>
