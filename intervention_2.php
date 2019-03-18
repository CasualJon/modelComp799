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
            <h3>Another Trial</h3>
            <p>In this next phase we will do the same experiment, except giving you a little more information about the classifier.</p>

            <br /><br />

            <h4>How the Model Performed Last Round:</h4>
          </div> <!-- /column -->
        </div> <!-- /row -->

        <!-- Confusion Matrix of Performance -->
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table borderless">
                <tbody>
                  <tr class="text-center">
                    <th style="width: 22%">
                    </th>
                    <th style="width: 38%">
                    </th>
                    <th style="width: 38%">
                    </th>
                  </tr>
                  <tr class="text-center">
                    <td>
                      <!-- Unused Cell -->
                    </td>
                    <td>
                      <h5>Actual Dog</h5>
                    </td>
                    <td>
                      <h5>Actual Cat</h5>
                    </td>
                  </tr>
                  <tr class="text-center">
                    <td style="vertical-align: middle;">
                      <h5>Predicted Dog</h5>
                    </td>
                    <td  style="vertical-align: middle; background-color: rgba(69, 128, 74, 0.4);">
                      <h5>8</h5>
                    </td>
                    <td style="vertical-align: middle; background-color: rgba(237, 32, 37, 0.4);">
                      <h5>4</h5>
                    </td>
                  </tr>
                  <tr class="text-center">
                    <td style="vertical-align: middle;">
                      <h5>Predicted Cat</h5>
                    </td>
                    <td style="vertical-align: middle; background-color: rgba(237, 32, 37, 0.4);">
                      <h5>0</h5>
                    </td>
                    <td style="vertical-align: middle; background-color: rgba(69, 128, 74, 0.4);">
                      <h5>4</h5>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br /><br /><br />

        <div class="row">
          <div class="col-md-12">
            <hr />
            <br />
            <ul>
              <li>In this second trial, you will be presented with 12 unique images</li>
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
              <li>Correct answers that <span style="color: #007BFF">YOU classify are worth 2 points</span></li>
              <li>Correct answers that the <span style="color: #28A745">Machine Learning model classifies are worth 3 points</span></li>
              <br /><br />

              <li>The Machine Learning model's overall accuracy is 67%</li>
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
