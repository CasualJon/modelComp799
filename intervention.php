<?php
  //Session, control vars and database connection settings
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';

  //Check that the connection proceeded to this page by internal reference
  $refuri = parse_url($_SERVER['HTTP_REFERER']);
  if($refuri['host'] != $localhost_domain && $refuri['host'] != $allowed_ext_refer) {
    $_SESSION['message'] = "If you disabled referrer settings in your browser, we cannot validate your ability to access this page.";
    header("location: ./error.php");
    exit;
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
          <h3><span id="question_title"></span></h3>
        </div> <!-- /column -->
        <div class="col-md-4">
          <h3 class="text-right" id="score_space">Score: <span id="points_total">0</span></h3>
        </div> <!-- /column -->
      </div> <!-- /row -->
      <hr />

      <form action="./survey.php" method="post">
        <div class="row">
          <div class="col-md-12">
            <h3>The Intervention</h3>

            <p>Begin<br /><br />The<br /><br />Intervention</p>
            <br /><br />

          </div> <!-- /column -->
        </div> <!-- /row -->
        <br /><br />

        <div class="row">
          <div class="col-md-12 text-center">
            <button class="btn btn-lg btn-outline-danger" id="continue_button" disabled>
              <b style="font-size: 38px">CONTINUE<b>
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
    <script src="./assets/js/intervention.js"></script>
  </body>
</html>
