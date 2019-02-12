<?php
  session_start();
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Log in to experiment site with admin credentials.">
    <meta name="author" content="UW-Madison Graphics Group">
    <?php include './php_includes/favicon.html'; ?>

    <title>Admin Login</title>
    <?php include './assets/css/style.html'; ?>
  </head>

  <body>
    <div class="container">
      <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
          <p>Admin Login!</p>
          <div class="form">
            <form action="./php_includes/admin_verify.php" method="post" autocomplete="off">
              <div class="field-wrap">
                <label>Id</label>
                <input type="text" required autocomplete="off" name="uid" autofocus />
              </div>

              <div class="field-wrap">
                <label>Password</label>
                <input type="password" required autocomplete="off" name="password" />
              </div>

              <button type="submit" class="btn btn-dark" name="login" style="float: right"><i class="fas fa-arrow-right"></i></button>
            </form>
          </div> <!-- /form -->


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
    <script src="./assets/js/admin_login.js"></script>
  </body>
</html>
