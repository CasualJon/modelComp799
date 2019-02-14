<?php
  //****************************************************************************
  //Assumes Informed Consent was obtained from the referring site, MTurk
  //****************************************************************************

  //Session, control vars and database connection settings
  //Server should keep session data for 2 hours (90 min timeout)
  ini_set('session.gc_maxlifetime', 7200);
  //Client should remember their session id for 2 hours
  session_set_cookie_params(7200);
  //Start session
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';

  if (!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    $_SESSION['admin'] = 0;

    //If here, connection exists.
    //Validate that this connection is from MTurk and that this IP Address has not
    //previously completed a survey. If not from MTruk, check if user is admin
    $refuri = parse_url($_SERVER['HTTP_REFERER']);
    if($refuri['host'] != $allowed_ext_refer) {
      header("location: ./admin_login.php");
      exit;
    }

    //If the connection was sent through MTruk, validate that we've not
    //encountered this person before
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
      $_SESSION['message'] = "We're sorry, but you can only complete this HIT survey once. This IP Address completed the survey on ".$worker_data['visit_date'].".";
      unset($ip_address, $query, $worker_data);
      header("location: ./error.php");
      exit;
    }

    //Set worker data into the table
    $resultSet->free();
    $query = "INSERT INTO workers (ip_address, visit_date, start_time) ";
    $query .= "VALUES (?, ?, ?)";
    date_default_timezone_set("America/Chicago");
    $curr_time = date("h:i a");
    $curr_date = date("Y-m-d");
    $ip_stmt->prepare($query);
    $ip_stmt->bind_param("sss", $ip_address, $curr_date, $curr_time);
    $ip_stmt->execute();
    unset($query, $curr_date, $curr_time);

    //Retrieve the interal identifier set into the worker table from last action
    $query = "SELECT internal_identifier FROM workers WHERE ip_address=?";
    $ip_stmt->prepare($query);
    $ip_stmt->bind_param("s", $ip_address);
    $ip_stmt->execute();
    $resultSet = $ip_stmt->get_result();
    $internal_id = $resultSet->fetch_assoc();
    $resultSet->free();
    $_SESSION['internal_identifier'] = $internal_id['internal_identifier'];
    unset($ip_address, $internal_id);
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Experiment main page.">
    <meta name="author" content="UW-Madison Graphics Group">
    <?php include './php_includes/favicon.html'; ?>

    <title>UW-Madison Graphics</title>
    <?php include './assets/css/style.html'; ?>
  </head>

  <body>
    <div class="container">
      <?php
        if ($_SESSION['admin'] === 1) {
          echo '
            <div class="row">
              <div class="col-md-12 admin-banner">
                <h2>Admin Mode</h2>
              </div>
            </div>
          ';
        }
      ?>
      <form action="./survey.php" method="post">
        <div class="row">
          <div class="col-md-12">
            <h3>UW-Madison Graphics Group Research - Experiment Explanation</h3>

            <p>Experiment<br /><br />Explanation<br /><br />Statement</p>
            <br /><br />

            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="exp_acknowledge" name="exp_acknowledge">
              <label class="custom-control-label consent_text" for="exp_acknowledge" id="exp_lbl">
                I have read the Experiment Explanation and understand what is expected of me.
              </label>
            </div>

          </div> <!-- /column -->
        </div> <!-- /row -->
        <br /><br />

        <div class="row">
          <div class="col-md-12 text-center">
            <button class="btn btn-lg btn-outline-danger" id="begin_button" disabled>
              <b style="font-size: 38px">BEGIN<b>
            </button>
          </div> <!-- /column -->
        </div> <!-- /row -->
      </form>

      <?php
        if ($_SESSION['admin'] === 1) {
          echo "<hr /><p>";
          var_dump($_SESSION);
          echo "<p>";
        }
      ?>
    </div> <!-- /container -->
    <?php include './assets/js/universal.html'; ?>
    <script src="./assets/js/index.js"></script>
  </body>
</html>
