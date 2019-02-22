<?php
  //****************************************************************************
  //Assumes Informed Consent was obtained from the referring site, MTurk
  //****************************************************************************

  //Session, control vars and database connection settings
  //Server should keep session data for 75 minutes (60 min timeout)
  ini_set('session.gc_maxlifetime', 4500);
  //Client should remember their session id for 90 minutes
  session_set_cookie_params(4500);
  //Start session
  session_start();
  require './php_includes/control_variables.php';
  require './php_includes/db.php';

  //If the user is returning to an active session, take them to the correct page
  //Survey should handle redirection to survey/intervention/thank you
  if (isset($_SESSION['survey'])) {
    header("location: ./survey.php");
    exit;
  }

  if (!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    $_SESSION['admin'] = 0;

    //If here, connection exists.
    //Validate that this connection is from MTurk and that this IP Address has not
    //previously completed a survey. If not from MTruk, check if user is admin
    $refuri = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    if(strcmp($refuri, $allowed_ext_refer) != 0) {
      header("location: ./admin_login.php");
      exit;
    }

    //If the connection was sent through MTruk, validate that we've not
    //encountered this person before
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $query = "SELECT * FROM workers WHERE ip_address=?";
    $ip_stmt = $mysqli->stmt_init();
    $ip_stmt->prepare($query);
    $ip_stmt->bind_param("s", $ip_address);
    $ip_stmt->execute();
    $resultSet = $ip_stmt->get_result();

    //We've seen this IP Address before, so reject the user
    if ($resultSet->num_rows > 0) {
      $worker_data = $resultSet->fetch_assoc();
      $resultSet->free();
      $ip_stmt->close();
      $_SESSION['message'] = "We're sorry, but you can only complete this HIT survey once. This IP Address completed the survey on ".$worker_data['visit_date'].".";
      unset($ip_address, $query, $worker_data);
      header("location: ./error.php");
      exit;
    }

    //Set worker data into the table
    $resultSet->free();
    $query = "INSERT INTO workers (ip_address, visit_date, start_time) ";
    $query .= "VALUES (?, ?, ?)";
    date_default_timezone_set("America/Chicago");
    $curr_time = date("h:i a");
    $curr_date = date("Y-m-d");
    $ip_stmt->prepare($query);
    $ip_stmt->bind_param("sss", $ip_address, $curr_date, $curr_time);
    $ip_stmt->execute();

    //Retrieve the interal identifier set into the worker table from last action
    $query = "SELECT internal_identifier FROM workers WHERE ip_address=?";
    $ip_stmt->prepare($query);
    $ip_stmt->bind_param("s", $ip_address);
    $ip_stmt->execute();
    $resultSet = $ip_stmt->get_result();
    $internal_id = $resultSet->fetch_assoc();
    $resultSet->free();

    $_SESSION['internal_identifier'] = $internal_id['internal_identifier'];
    $_SESSION['ip_address'] = $ip_address;
    $_SESSION['curr_date'] = $curr_date;

    unset($query, $curr_date, $curr_time, $ip_address, $internal_id);
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
      <form action="./survey.php" method="post">
        <!-- Simply adding some space from the top of the screen -->
        <div class="row">
          <div class="col-md-12">
            <p>&nbsp;</p>
            <hr />
          </div> <!-- /column -->
        </div> <!-- /row -->

        <!-- UWGG logo & title -->
        <div class="row">
          <div class="col-md-1">
            <img src="./assets/img/UWMGG.png" height="30px" />
          </div> <!-- /column -->
          <div class="col-md-11">
            <h4>UW-Madison Graphics Group Research</h4>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br />

        <!-- Begin experiment explanation -->
        <div class="row">
          <div class="col-md-12">
            <h3>Experiment Explanation</h3>
            <p>
              Hello, and thank you for agreeing to participate in this study on
              human understanding of Machine Learning models.
            </p>
            <p>
              During this brief (5-8 minutes) experiment, you can expect the following:
            </p>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br />

        <div class="row">
          <div class="col-md-12">
            <ul>
              <li>There are 2 trials in this experiment</li>
              <li>In the first trial, you will be presented with 12 unique images</li>
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
              <li>Correct answers that <span style="color: #007BFF">YOU classify are worth 3 points</span></li>
              <li>Correct answers that the <span style="color: #28A745">Machine Learning model classifies are worth 4 points</span></li>
              <br />

              <li>The Machine Learning model's overall accuracy is 75%</li>
            </ul>
          </div> <!-- /column -->
        </div> <!-- /row -->
        <br />
        <hr />

        <h3>UNIVERSITY OF WISCONSIN-MADISON</h3>
        <h5>
          Research Participant Information and Consent Form<br />
          <span class="semi_transp">(for online crowd-sourced experimentation)</span>
        </h5>
        <br />

        <h6 style="display: inline">Title of the Study: </h6><p>Human Understanding of Machine Learning Models</p>
        <h6 style="display: inline">Principal Investigator: </h6><p>Dr. Michael Gleicher (phone: (608) 263-2874)</p>
        <h6 style="display: inline">Student Researcher: </h6><p>Jon Cyrus (lab phone (608) 265-2711)</p>

        <p>
          <u>DESCRIPTION OF THE RESEARCH</u><br />
          You are invited to participate in a research study about how users
          perceive information presented using different kinds of charts,
          figures, and text.
        </p>
        <p>
          You have been asked to participate because we need to know what perceptual
          factors determine how easy it is to read visual displays of information
          given different amounts of data and different presentation styles.
        </p>
        <p>
          The purpose of the research is to determine what perceptual and cognitive
          factors are involved in how users read and react to large amounts of data,
          using different presentation techniques.
        </p>
        <p>
          This study will include English speakers from 18 to 65 years old, who
          feel comfortable using an online interface to read graphs, charts, or
          text and answer questions about data.
        </p>

        <p style="display: inline">To reiterate, you are eligible for this study if and only if:<br /></p>
        <ul>
          <li>You have <i>not already completed this study,</i></li>
          <li>You are able to follow textual direction in English,</li>
          <li>You are between 18-65 years of age,</li>
          <li>You do not have color blindness or any other color vision deficiency, and</li>
          <li>
            You have browser settings compatible with the experiment<br />
            (including, but not limited to, JavaScript enabled, HTTP Referrer header enabled, etc.)
          </li>
        </ul>

        <p>
          <u>WHAT WILL MY PARTICIPATION INVOLVE?</u><br />
          If you decide to participate in this research you will be asked to answer
          questions using a number of charts, figures, or passages to assist you in
          making decisions. You will be presented with an image, and then asked about
          the information that it displays. We expect this study to take 6-8 minutes.
        </p>

        <p>
          <u>ARE THERE ANY RISKS TO ME?</u><br />
          We don't anticipate any risks from participation in this study greater
          than normal activity.
        </p>

        <p>
          <u>ARE THERE ANY BENEFITS TO ME?</u><br />
          There are no direct benefits to you other than compensation.
        </p>

        <p>
          <u>WILL I BE COMPENSATED FOR MY PARTICIPATION?</u><br />
          You will receive $1.00 for participating in this study. We expect the
          study to take at most 10 minutes.
        </p>

        <p>
          If you do withdraw prior to the end of the study, you will receive no
          compensation.
        </p>

        <p>
          <u>HOW WILL MY CONFIDENTIALITY BE PROTECTED?</u><br />
          While there will probably be publications as a result of this study, your
          name will not be used. If there is a free-response field, we may use your
          quote in publication without your name.
        </p>

        <p>
          <u>WHOM SHOULD I CONTACT IF I HAVE QUESTIONS?</u><br />
          You may ask any questions about the research at any time by e-mailing
          the owner of the HIT or by calling the laboratory at (608) 265-2711.
          If you wish to escalate your issue, you may contact the Principal
          Investigator Dr. Michael Gleicher at (608) 263-2874.
        </p>

        <p>
          If you are not satisfied with response of research team, have more questions,
          or want to talk with someone about your rights as a research participant,
          you are encouraged to contact the Education and Social/Behavioral Science
          IRB Office at the University of Wisconsin-Madison at (608) 263-2320.
        </p>

        <p>
          Your participation is completely voluntary. If you begin participation and
          change your mind, you may end your participation at any time without penalty.
        </p>

        <p>
          By clicking "Ready" you confirm that you meet the criteria for this study
          and consent to participate. This will open up the study in a new window.
        </p>

        <div class="row">
          <div class="col-md-12 text-center">
            <button class="btn btn-lg btn-outline-danger" id="begin_button" disabled>
              <b style="font-size: 38px">Ready</b>
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
    <script src="./assets/js/index.js"></script>
  </body>
</html>
