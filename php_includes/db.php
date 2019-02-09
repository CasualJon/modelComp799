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

  //If here, connection exists.
  //Validate that this connection is from MTurk and that this IP Address has not
  //previously completed a survey
  $refuri = parse_url($_SERVER['HTTP_REFERER']);
  if($refuri['host'] != "mturk.com") {
    $_SESSION['debug'] = "In refuri-host != mturk";
    //TODO require password to proceed - used for admin testing and evaluation

    //If password is invalid
    if (false) {
      unset($refuri);
      $_SESSION['message'] = "We're sorry, but you can only complete the survey through Amazon's Mechanical Turk (<a href=\"https://www.mturk.com\">https://www.mturk.com</a>)";
      header("location: ./error.php");
      exit;
    }
  }
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $query = "SELECT * FROM workers WHERE ip_address=?";
  $ip_stmt = $mysqli->stmt_init();
  $ip_stmt->prepare($query);
  $ip_stmt->bind_param("s", $ip_address);
  $ip_stmt->execute();
  $resultSet = $ip_stmt->get_result();

  //We've seen this IP Address before, so reject the user
  if ($resultSet->num_rows > 0) {
    $worker_data = $resultSet->fetch_assoc();
    $resultSet->free();
    $ip_stmt->close();
    $_SESSION['message'] = "We're sorry, but you can only complete this HIT survey once. This IP Address completed the survey on ".$worker_data['visit_date']." using MTurk Worker ID ".$worker_data['mturk_worker_id'];
    unset($ip_address, $query, $worker_data);
    header("location: ./error.php");
    exit;
  }

  $resultSet->free();
  $query = "INSERT INTO workers (ip_address, visit_date, start_time) ";
  $query .= "VALUES (?, ?, ?)";
  $curr_date = date("Y-m-d");
  $curr_time = time() % 1400;
  $ip_stmt->prepare($query);
  $ip_stmt->bind_param("ssi", $ip_address, $curr_date, $curr_time);
  $ip_stmt->execute();
  $ip_stmt->close();
  unset($ip_address, $query, $curr_date, $curr_time);
?>
