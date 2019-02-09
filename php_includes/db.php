<?php
  //Database connection settings
  $host = "localhost";
  $dbUId = "phpmyadmin";
  $pass = "cs799";
  $db = "model_understanding_spring19";

  //Check to see if $mysql already exists, instantiate if not
  if (!isset($mysqli)) {
    $mysqli = new mysqli($host, $dbUId, $pass, $db);
    if (!$mysqli->set_charset("utf8mb4")) {
      $db_issue = "Database error loading UTF8 character set.<br /> We're working on it, but please feel free to reach out and let us know about Error Code #".$mysqli->errno;
    }
  }
  //Validate connection
  if ($mysqli->connect_error != NULL) {
    $db_issue = "<b>Database connection failed.</b><br /> We're working on it, but please feel free to reach out and let us know about Connection Error Code #".$mysqli->connect_errno;
  }

  if (isset($db_issue)) {
    if (!empty($_SESSION['message'])) $_SESSION['message'] += "<br />".$db_issue;
    else $_SESSION['message'] = $db_issue;

    header("location: ./error.php");
    exit;
  }
?>
