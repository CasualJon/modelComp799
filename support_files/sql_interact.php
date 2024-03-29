<?php
  session_start();
  require '../php_includes/db.php';
  require '../php_includes/control_variables.php';

  //PHP set for functions to call SQL for page, called by Ajax in JS
  header('Content-Type: application/json');
  $result = array();

  //Validate that function & argument parameters were passed in from JS
  if (!isset($_POST['functionname'])) {
    $result['error'] = 'No function name provided.';
  }
  if (!isset($_POST['arguments'])) {
    $result['error'] = 'No function arguments provided.';
  }
  //Execute code via switch
  if (!isset($result['error'])) {
    switch ($_POST['functionname']) {
      case 'get_survey_control':
        $result['survey'] = $_SESSION['survey'];
        $image_paths = array();
        $loop_start = $_SESSION['survey']['curr_question'] * $images_per_question;
        $loop_end = ($_SESSION['survey']['curr_question'] + 1) * $images_per_question;
        for ($i = $loop_start; $i < $loop_end; $i++) {
          array_push($image_paths, $img_source.$_SESSION['exp_data'][$i]);
        }
        $result['image_paths'] = $image_paths;

        //POST[0] = post_intervention when the user has completed the intervention
        if (strcmp($_POST['arguments'][0], 'post_intervention') == 0) {
          $_SESSION['survey']['intervention_comp'] = true;
        }

        //If interventions are active, and we have not completed the intervention,
        //this is the case to handle starting with an intervention
        if ($intervention_trigger &&
            !$_SESSION['survey']['intervention_comp'] &&
            $_SESSION['survey']['curr_question'] == $intervention_count) {

          $result['intervention'] = true;
        }

        //If the user completed the total number of listed questions
        if ($_SESSION['survey']['curr_question'] >= $num_questions) {
          $result['complete'] = true;
        }
        break;

      case 'get_next_question':
        //Make sure user has not exceeded max_allowed_time
        $now_time = time();
        if ($now_time - $_SESSION['survey']['begin_time'] > $max_allowed_time) {
          //If time exceeded, then set error message and redirect
          $_SESSION['message'] = "We're sorry, but you exceeded the time allowed in completing this survey.";
          header("location: ../error.php");
          exit;
        }

        //Set the response from the user
        $sel_a = ($_SESSION['survey']['curr_question'] * $images_per_question) + $_POST['arguments'][0];
        $sel_b = ($_SESSION['survey']['curr_question'] * $images_per_question) + $_POST['arguments'][1];
        $sel_c = ($_SESSION['survey']['curr_question'] * $images_per_question) + $_POST['arguments'][2];
        $sel_d = ($_SESSION['survey']['curr_question'] * $images_per_question) + $_POST['arguments'][3];
        $response_val = array(
          'question#' => $_SESSION['survey']['curr_question'],
          'selection_a' => "".$_POST['arguments'][0]."^".$_SESSION['exp_data'][$sel_a],
          'selection_b' => "".$_POST['arguments'][1]."^".$_SESSION['exp_data'][$sel_b],
          'selection_c' => "".$_POST['arguments'][2]."^".$_SESSION['exp_data'][$sel_c],
          'selection_d' => "".$_POST['arguments'][3]."^".$_SESSION['exp_data'][$sel_d],
          'seconds_taken' => $_POST['arguments'][4]
        );
        array_push($_SESSION['survey']['response'], $response_val);

        //Increment question counter (how many user has completed)
        $_SESSION['survey']['curr_question']++;

        //If interventions are active and we've just completed the question count
        //required to trigger them, redirect to the intervention page
        if ($intervention_trigger && $_SESSION['survey']['curr_question'] == $intervention_count) {
          //Get current intervention counts and make best decision about which to assign
          $intervention_data = array();
          $stmt = $mysqli->stmt_init();
          $query = "SELECT * FROM interventions";
          $stmt->prepare($query);
          $stmt->execute();
          $resultSet = $stmt->get_result();
          if ($resultSet->num_rows > 0) {
            while ($row = $resultSet->fetch_assoc()) {
              array_push($intervention_data, (int)$row['count']);
            }
          }
          $lowest_index = 0;
          $lowest_val = $intervention_data[0];
          for ($i = 0; $i < sizeof($intervention_data); $i++) {
            //If the intervention we're evaluating has it's equal share of the
            //total number of runs, skip past it
            if ($intervention_data[$i] >= $total_mturk_runs / sizeof($intervention_data)) {
              continue;
            }
            //If this intervention has been used 0 times, use it
            if ($intervention_data[$i] == 0) {
              $lowest_index = $i;
              break;
            }
            //If this intervention is lower than the others, use it
            if ($intervention_data[$i] < $lowest_val) {
              $lowest_val = $intervention_data[$i];
              $lowest_index = $i;
            }
          }
          $intervention_name = "../../intervention_".$lowest_index.".php";
          $_SESSION['intervention_id'] = $lowest_index;
          $result['intervention'] = $intervention_name;
        }

        //If the user completed the total number of listed questions
        if ($_SESSION['survey']['curr_question'] == $num_questions) {
          $result['complete'] = true;
        }

        //Return the current survey data and question
        $result['survey'] = $_SESSION['survey'];
        $image_paths = array();
        $loop_start = $_SESSION['survey']['curr_question'] * $images_per_question;
        $loop_end = ($_SESSION['survey']['curr_question'] + 1) * $images_per_question;
        for ($i = $loop_start; $i < $loop_end; $i++) {
          array_push($image_paths, $img_source.$_SESSION['exp_data'][$i]);
        }
        $result['image_paths'] = $image_paths;
        break;

      case 'file_demographics':
        $query = "UPDATE responses SET gender=?, age=?, comments=? WHERE internal_identifier=?";
        $demo_stmt = $mysqli->stmt_init();
        $demo_stmt->prepare($query);
        $demo_stmt->bind_param("sssi", $_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_SESSION['internal_identifier']);
        $demo_stmt->execute();
        $demo_stmt->close();
        break;

      default:
        $result['error'] = "Default case in switch: invalid function call.";
        break;
    }
  }

  if (isset($_POST['functionname'])) unset($_POST['functionname']);
  if (isset($_POST['arguments'])) unset($_POST['arguments']);

  // unset($_SESSION['target_id']);
  echo json_encode($result);
?>
