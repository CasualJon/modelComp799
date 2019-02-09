<?php
  session_start();
  echo "<p>".$_SESSION['message']."</p>";

  if (isset($_SESSION['debug'])) echo "<p>".$_SESSION['debug']."</p>";

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Error.">
    <meta name="author" content="UW-Madison Graphics Group">

    <title>Error</title>
    <?php include './assets/css/style.html'; ?>
  </head>

  <body>
    <div class="container">
      <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
          <p>Error Page!</p>
          <div class="form">
            <?php
              if (isset($_SESSION['message'])) echo "<p>".$_SESSION['message']."</p>";
            ?>
          </div> <!-- /form -->

          <?php
            if ($_SESSION === 1) {
              echo "<hr /><p>";
              var_dump($_SESSION);
              echo "<p>";
            }
          ?>
        </div> <!-- /column -->
      </div> <!-- /row -->
    </div> <!-- /container -->

    <?php include './assets/js/universal.html'; ?>
  </body>
</html>
