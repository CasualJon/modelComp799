<?php
  session_start();
  require './php_includes/db.php';

  if (!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    $_SESSION['admin'] = 0;

    //If here, connection exists.
    //Validate that this connection is from MTurk and that this IP Address has not
    //previously completed a survey. If not from MTruk, check if user is admin
    $refuri = parse_url($_SERVER['HTTP_REFERER']);
    if($refuri['host'] != "mturk.com") {
      header("location: ./admin_login.php");
      exit;
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
      <div class="row">
        <div class="col-md-12">
          <h4>UW-Madison Graphics Group Research - Informed Consent</h4>

          <p>Infomred<br /><br />Consnet<br /><br />Statement</p>

        </div> <!-- /column -->
      </div> <!-- /row -->

      <div class="row">
        <div class="col-md-4 offset-md-4">
          <button class="btn btn-lg btn-outline-danger"><b style="font-size: 44px; width: 100%">CONTINUE<b></button>
        </div> <!-- /column -->
      </div> <!-- /row -->

          <?php
            if ($_SESSION['admin'] === 1) {
              echo "<hr /><p>";
              var_dump($_SESSION);
              echo "<p>";
            }
          ?>
        </div> <!-- /column -->
      </div> <!-- /row -->
    </div> <!-- /container -->
    <?php include './assets/js/universal.html'; ?>
    <script src="informed_consent.js"></script>
  </body>
</html>
