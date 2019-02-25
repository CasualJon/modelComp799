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
          <h4>
            If you arrived at this page...
          </h4>
          <p>
            The website will launch to an administrative log-in page if the address
            is typed/copy-pasted into the address bar, linked from elsewhere,
            or you have the HTTP Referer Header turned off in your browser settings.
            <br /><br />
            If you are are unsure about your HTTP Referer Header setting:
          </p>
          <ul>
            <li>Firefox:</li>
            <ul>
              <li>Type <b><i>about:config</i></b> into your browser's address bar</li>
              <li>It may launch a pop-up warning you to be careful with adjusting settings</li>
              <li>In the search bar, type "referer" and the last entry should be "network.http.sendRefererHeader"</li>
              <li>If the value is a 1 or a 2, you should be set</li>
            </ul>
            <li>Chrome:</li>
            <ul>
              <li>Type <b><i>chrome://flags</i></b> into the browser's address bar</li>
              <li>This will bring up a page with a warning</li>
              <li>In the search bar, type "referer" and the top entry should be a setting called "Reduce default 'referer' header granularity"</li>
              <li>If the value is "disabled" you should be set</li>
            </ul>
          </ul>
          <div class="form">
            <form action="./php_includes/admin_verify.php" method="post" autocomplete="off">
              <h4>Admin Login</h4>
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
            echo "<hr /><p>";
            $refuri = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            echo $refuri;
            echo "</p>";

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
