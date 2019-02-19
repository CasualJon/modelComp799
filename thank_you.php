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

  $hit_completion_code = "".$_SESSION['ip_address']."|".$_SESSION['$curr_date'];
  $hit_completion_code = md5($hit_completion_code);

  if (!isset($_SESSION['done']) && $_SESSION['admin'] != 1) {
    $data = "".$_SESSION['internal_identifier'].",";
    $data .= $_SESSION['survey']['mid_score'].",";
    $data .= $_SESSION['survey']['score'].",";
    for ($i = 0; $i < sizeof($_SESSION['survey']['response']); $i++) {
      $data .= $_SESSION['survey']['response'][$i]['question#'].",";
      $data .= $_SESSION['survey']['response'][$i]['quest_name'].",";
      $data .= $_SESSION['survey']['response'][$i]['correctness'].",";
      $data .= $_SESSION['survey']['response'][$i]['eval_method'].",";
      $data .= $_SESSION['survey']['response'][$i]['user_val'].",";
    }
    $result_stmt = $mysqli->stmt_init();
    $result_stmt->prepare("INSERT INTO responses VALUES(?, ?, ?, ?)");
    $result_stmt->bind_param("iiis", $_SESSION['internal_identifier'], $_SESSION['survey']['mid_score'], $_SESSION['survey']['score'], $data);
    $result_stmt->execute();
    $result_stmt->close();

    $curr_time = date("h:i a");
    $query = "UPDATE workers SET end_time=?, hit_completion_code=? WHERE internal_identifier=?";
    $comp_stmt = $mysqli->stmt_init();
    $comp_stmt->prepare($query);
    $comp_stmt->bind_param("ssi", $curr_time, $hit_completion_code, $_SESSION['internal_identifier']);
    $comp_stmt->execute();
    $comp_stmt->close();
  }

  $_SESSION['done'] = true;
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
      <div class="row">
        <div class="col-md-8">
          <h3><span id="question_title"></span></h3>
        </div> <!-- /column -->
        <div class="col-md-4">
          <h3 class="text-right" id="score_space">Score: <span id="points_total">0</span></h3>
        </div> <!-- /column -->
      </div> <!-- /row -->
      <hr />

      <div class="row">
        <div class="col-md-12">
          <h3>Thank You!</h3>

          <p>We're grateful for your help in this study. Your Amazon MTurk HIT Completion Code is below.</p>
          <p>Please be aware that you can only participate in this study a single time. Subsequent attempts
             to work this HIT may jeopardize the validity of your completion code.</p>

          <h4>Amazon MTurk HIT Completion Code: </h4>
          <input type="text" class="copy_code" id="hit_comp_code" value=<?php echo "\"".$hit_completion_code."\"" ?> />
          <button type="button" class="btn" onclick="copyTextToClipboard()">Copy Code</button>
          <br /><br />

          <p>Thanks again,<br />
             University of Wisconsin-Madison Graphics Group</p>
          <img src="./assets/img/UWMGG.png" alt="" />

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
    <script src="./assets/js/thank_you.js"></script>
  </body>
</html>
