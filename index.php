<?php
  //****************************************************************************
  //Assumes Informed Consent was obtained from the referring site, MTurk
  //****************************************************************************

  //Session, control vars and database connection settings
  //Server should keep session data for 75 minutes (60 min timeout)
  ini_set('session.gc_maxlifetime', 4500);
  //Client should remember their session id for 90 minutes
  session_set_cookie_params(4500);
  //Start session
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';

  //If the user is returning to an active session, take them to the correct page
  //Survey should handle redirection to survey/intervention/thank you
  if (isset($_SESSION['survey'])) {
    header("location: ./survey.php");
    exit;
  }

  if (!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    $_SESSION['admin'] = 0;

    //If here, connection exists.
    //Validate that this connection is from MTurk and that this IP Address has not
    //previously completed a survey. If not from MTruk, check if user is admin
    $refuri = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    if(strcmp($refuri, $allowed_ext_refer) != 0) {
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

    //Retrieve the interal identifier set into the worker table from last action
    $query = "SELECT internal_identifier FROM workers WHERE ip_address=?";
    $ip_stmt->prepare($query);
    $ip_stmt->bind_param("s", $ip_address);
    $ip_stmt->execute();
    $resultSet = $ip_stmt->get_result();
    $internal_id = $resultSet->fetch_assoc();
    $resultSet->free();

    $_SESSION['internal_identifier'] = $internal_id['internal_identifier'];
    $_SESSION['ip_address'] = $ip_address;
    $_SESSION['curr_date'] = $curr_date;

    unset($query, $curr_date, $curr_time, $ip_address, $internal_id);
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
        <!-- Simply adding some space from the top of the screen -->
        <div class="row">
          <div class="col-md-12">
            <p>&nbsp;</p>
            <hr />
          </div> <!-- /column -->
        </div> <!-- /row -->

        <!-- UWGG logo & title -->
        <div class="row">
          <div class="col-md-1">
            <img src="./assets/img/UWMGG.png" height="30px" />
          </div> <!-- /column -->
          <div class="col-md-11">
            <h4>UW-Madison Graphics Group Research</h4>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br />

        <!-- Begin experiment explanation -->
        <div class="row">
          <div class="col-md-12">
            <h3>Experiment Explanation</h3>
            <p>
              Hello, and thank you for agreeing to participate in this study on
              human understanding of Machine Learning models.
            </p>
            <p>
              During this brief (5-8 minutes) experiment, you can expect the following:
            </p>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br />

        <div class="row">
          <div class="col-md-12">
            <ul>
              <li>In the first trial, you will be presented with 12 unique images</li>
              <li>The main subject in each image is either a dog or a cat</li>

              <img src="./assets/img/examples/E01W.jpg" height="42px" style="display: inline"/>
              &nbsp;&nbsp;
              <img src="./assets/img/examples/L12E.jpg" height="42px" />
              <br /><br /><br />

              <li>
                For each image, select whether to classify the image yourself
                (determine if it contains a dog or a cat) or to allow
                a Machine Learning-trained model to classify the image
              </li>
              <li>Correct answers that <span style="color: #007BFF">YOU classify are worth 3 points</span></li>
              <li>Correct answers that the <span style="color: #28A745">Machine Learning model classifies are worth 4 points</span></li>
              <br /><br />

              <li>The Machine Learning model's overall accuracy is 75%</li>
            </ul>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br /><br />

        <div class="row">
          <div class="col-md-12 text-center">
            <button class="btn btn-lg btn-outline-danger" id="begin_button" disabled>
              <b style="font-size: 38px">BEGIN</b>
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
