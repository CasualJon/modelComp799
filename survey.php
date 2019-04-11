<?php
  //Session, control vars and database connection settings
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';
  if (!isset($_SESSION['exp_data'])) {
    require './php_includes/survey_setup.php';
  }

  if (!isset($_SESSION['exp_data']) || empty($_SESSION['exp_data'])) {
    $_SESSION['message'] = "An error occurred in configuring the survey data.";
    header("location: ./error.php");
    exit;
  }

  //Set SESSION variables to track survey control
  if (!isset($_SESSION['survey'])) {
    $_SESSION['survey']['begin_time'] = time();
    $_SESSION['survey']['intervention_comp'] = false;
    $_SESSION['survey']['curr_question'] = 0;
    $_SESSION['survey']['response'] = array();
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
    <?php include './assets/css/styleIn.html'; ?>
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

      <!-- Survey header information (updated by JS) -->
      <div class="row">
        <div class="col-md-12 text-center">
          <br />
          <h3>
            Select the 4 images you believe most likely to be <i>incorrectly</i>
            identified <br /> by the Machine Learning model that achieves 75% accuracy.
          </h3>
        </div> <!-- /column -->
      </div> <!-- /row -->
      <hr />

      <!-- Survey question data -->
      <div class="row">
        <div class="col-md-12 text-center">
          <span id="question_space"></span>
        </div> <!-- /column -->
      </div> <!-- /row -->
      <br />

      <!-- Continue button -->
      <div class="row">
        <div class="col-md-12 text-center">
          <button class="btn btn-lg btn-outline-danger" id="continue_button" onclick="executeUserSelection()" style="display: none" disabled>
            <b style="font-size: 38px">Continue</b>
          </button>
        </div> <!-- /column -->
      </div> <!-- /row -->

      <?php
        if ($_SESSION['survey']['intervention_comp']) {
          switch ($_SESSION['intervention_id']) {
            case 1:
              include './php_includes/int_1_reminder.php';
              break;
            case 2:
              include './php_includes/int_2_reminder.php';
              break;
            case 3:
              include './php_includes/int_3_reminder.php';
              break;
            case 4:
              include './php_includes/int_4_reminder.php';
              break;
            case 0:
            default:
              break;
          }
        }

        if ($_SESSION['admin'] === 1) {
          echo "<hr /><p>";
          var_dump($_SESSION);
          echo "<p>";
        }
      ?>

    </div> <!-- /container -->
    <?php include './assets/js/universal.html'; ?>
    <script src="./assets/js/survey.js"></script>
  </body>
</html>
