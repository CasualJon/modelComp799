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
    $_SESSION['survey']['score'] = 0;
    $_SESSION['survey']['mid_score'] = 0;
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

      <!-- Survey header information (updated by JS) -->
      <div class="row">
        <div class="col-md-8">
          <h3><span id="question_title">The Survey</span></h3>
        </div> <!-- /column -->
        <div class="col-md-4">
          <h3 class="text-right" id="score_space">Score: <span id="points_total">0</span></h3>
        </div> <!-- /column -->
      </div> <!-- /row -->
      <hr />

      <!-- Survey question data -->
      <div class="row">
        <div class="col-md-6 text-center">
          <span id="question_space"></span>
        </div> <!-- /column -->
        <div class="col-md-6 text-center">
          <span id="respond_space"></span>
        </div> <!-- /column -->
      </div> <!-- /row -->

      <?php
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
