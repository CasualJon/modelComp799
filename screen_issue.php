<?php
  session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Error.">
    <meta name="author" content="UW-Madison Graphics Group">
    <?php include './php_includes/favicon.html'; ?>

    <title>Error</title>
    <?php include './assets/css/styleOut.html'; ?>
  </head>

  <body>
    <div class="container">
      <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
          <h4>Mobile Device/Abbreviated Screen Detected</h4>

          <div class="form">
            <p>
              Thank you for your interest in participating in this research study.
              Unfortunately, the nature of the experiment and its data requires
              a larger screen.
            </p>
            <p>
              If you are using a mobile device, you may re-launch this HIT from
              worker.mturk.com in the browser of a desktop or laptop.
            </p>
            <p>
              If you are using a laptop or desktop, please ensure that you have
              not reduced the width of your browser below 940 pixels in width.
            </p>
          </div> <!-- /form -->

          <?php
            //Remove the entry in the workers table to allow the work to re-try
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $query = "DELETE FROM workers WHERE ip_address=?";
            $ip_stmt = $mysqli->stmt_init();
            $ip_stmt->prepare($query);
            $ip_stmt->bind_param("s", $ip_address);
            $ip_stmt->execute();

            //Clear session info
            session_set_cookie_params(time() - 3600);
            session_unset();
            session_destry();
          ?>
        </div> <!-- /column -->
      </div> <!-- /row -->
    </div> <!-- /container -->

    <?php include './assets/js/universal.html'; ?>
  </body>
</html>
