<?php
  //Session, control vars and database connection settings
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';

  //Get the images for the confusion matrix
  $dir_contents = scandir($_SESSION['matrix_directory']);
  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);
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
        <div class="col-md-12">
          <h3><span id="question_title" style="color: transparent">.</span></h3>
        </div> <!-- /column -->
      </div> <!-- /row -->
      <hr />

      <form action="./survey.php" method="post">
        <div class="row">
          <div class="col-md-12">
            <h3>Another Trial</h3>
            <p>In this next phase we will do the same experiment, except giving you a little more information about the classifier.</p>

            <br /><br />

            <h4>How the Model Works</h4>
          </div> <!-- /column -->
        </div> <!-- /row -->

        <div class="row">
          <div class="col-md-12">
            <h5><u>Cats:</u> 50% accuracy</h5>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br />

        <div class="row">
          <div class="col-md-12">
            <hr />
            <br />
            <ul>
              <li>In this second trial, you will be presented with 16 unique images in table format.</li>
              <li>The main subject in each image is either a dog or a cat.</li>

              <img src="./assets/img/examples/E01W.jpg" height="42px" style="display: inline"/>
              &nbsp;&nbsp;
              <img src="./assets/img/examples/L12E.jpg" height="42px" />
              <br /><br /><br />

              <li>
                We've trained an image classification model using Machine Learning.<br />
                <span class="semi_transp">Basically, we have an AI-like tool that determines whether the picture contains a dog or a cat.</span>
              </li>
              <br />

              <li>
                Your task is to choose the images you believe the Machine Learning model will <i style="color: yellow">incorrectly</i> identify.<br />
                <span class="semi_transp">In other words, select the pictures you think this AI-like tool will call a dog when it's actually a cat, and vice-versa.</span>
              </li>
              <br />

              <li>
                The Machine Learning model's overall accuracy is 75%.<br />
                <span class="semi_transp">This means you will need to identify 4 images (out of 16).</span>
              </li>
            </ul>
            <div class="text-center">
              <button class="btn btn-lg btn-outline-danger" id="continue_button" disabled>
                <b style="font-size: 38px">Continue</b>
              </button>
            </div>
          </div> <!-- /column -->
        </div> <!-- /row -->
      </form>

      <?php
        if ($_SESSION['admin'] === 1) {
          echo "<hr /><p>";
          // var_dump($_SESSION);
          echo $_SESSION['matrix_directory']."<br>";
          var_dump($dir_contents);
          echo "<p>";
        }
      ?>

    </div> <!-- /container -->
    <?php include './assets/js/universal.html'; ?>
    <script src="./assets/js/intervention.js"></script>
  </body>
</html>
