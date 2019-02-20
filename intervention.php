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
            <h3>Intervention</h3>
            <br />

            <?php
              if ($_SESSION['survey']['score'] <= 18) {
                echo "<p>Ok, I think I see what's going on...</p>";
              }
              else if ($_SESSION['survey']['score'] < 36) {
                echo "<p>Hey, not bad! <br />I can see you're mixing it up and trying to out think the model :D</p>";
              }
              else if ($_SESSION['survey']['score'] == 36) {
                echo "<p>Hey, pretty good! <br />Either the model got a few wrong, or you identified all of the images correctly by yourself :D</p>";
              }
              else {
                echo "<p>Wow! Looks like you outsmarted the model a couple of times! <br />You're doing better than average :D</p>";
              }
            ?>
            <br /><br />

            <h4>How the Model Works</h4>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <div class="row">
          <div class="col-md-2">
            <h5><u>Dogs:</u> </h5>
          </div> <!-- /column -->
          <div class="col-md-10">
            <ul>
              <li>Accuracy: 100%</li>
              <li>The Machine Learning model will <i>always</i> correctly classify dogs</li>
            </ul>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br /><br />

        <div class="row">
          <div class="col-md-2">
            <h5><u>Cats:</u> </h5>
          </div> <!-- /column -->
          <div class="col-md-10">
            <ul>
              <li>Accuracy: 50%</li>
              <li>The Machine Learning model will <span style="color: yellow"><i>never</i></span> correctly classify a cat that is clearly <span style="color: yellow">outdoors</span>. Classify these yourself with the <span style="color: #007BFF">blue button</span></li>
              <li>The Machine Learning model correctly classifies cats that are indoors. It is safe to allow the model to classify these with the <span style="color: #28A745">green button</span></li>
            </ul>
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
