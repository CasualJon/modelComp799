<?php
  //Session, control vars and database connection settings
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';

  //Check that the connection proceeded to this page by internal reference
  $refuri = parse_url($_SERVER['HTTP_REFERER']);
  if($refuri['host'] != $localhost_domain) {
    $_SESSION['message'] = "Mistakes were made.";
    header("location: ./error.php");
    exit;
  }
  if (!isset($_POST['consent_agree']) || strcmp($_POST['consent_agree'], "on")) {
    $_SESSION['message'] = "We do not see an Infomred Consent Agreement acknowledgement.";
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
          <h4>UW-Madison Graphics Group Research - Experiment Explanation</h4>

          <p>Experiment<br /><br />Explanation<br /><br />Statement</p>
          <br /><br />

        </div> <!-- /column -->
      </div> <!-- /row -->
      <br /><br />

      <div class="row">
        <div class="col-md-12 text-center">
          <button class="btn btn-lg btn-outline-danger" id="continue_button" onclick="beginExperiment()" disabled>
            <b style="font-size: 38px">BEGIN<b>
          </button>
        </div> <!-- /column -->
      </div> <!-- /row -->

      <?php
        if ($_SESSION['admin'] === 1) {
          echo "<hr /><p>";
          var_dump($_SESSION);
          echo "<br />";
          var_dump($_POST);
          echo "<p>";
        }
      ?>

    </div> <!-- /container -->
    <?php include './assets/js/universal.html'; ?>
    <script src="./assets/js/exp_explanation.js"></script>
  </body>
</html>
