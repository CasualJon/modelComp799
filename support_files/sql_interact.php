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
        $result['question_path'] = $img_source.$_SESSION['exp_data'][$_SESSION['survey']['curr_question']];
        $result['sel_options'] = $user_sel_opt_name;

        //POST[0] = post_intervention when the user has completed the intervention
        if (strcmp($_POST['arguments'][0], 'post_intervention') == 0) {
          $_SESSION['survey']['intervention_comp'] = true;
          $_SESSION['survey']['mid_score'] = $_SESSION['survey']['score'];
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

        //Save the data of the user's response to the just-answered question
        if (!is_null($_POST['arguments']) && !empty($_POST['arguments'])) {
          //Get the correctness of the response for ML Classification
          $q_correct_char = substr($_SESSION['exp_data'][$_SESSION['survey']['curr_question']], $ml_indicator_index, 1);
          $ml_correctness = intval($q_correct_char);
          //Mod 2 to reduce... 0 == false, 1 == true
          $ml_correctness = $ml_correctness % 2;

          //If ML scored (arguments[0] == 1), udpate score with correctness
          if ($_POST['arguments'][0] == 1 && $ml_correctness) {
            $_SESSION['survey']['score'] += $model_sel_points;
          }
          //If user scored (arguments[0] == 0), update score if their choice matches
          $user_correctness = NULL;
          if ($_POST['arguments'][0] == 0) {
            $q_class_char = substr($_SESSION['exp_data'][$_SESSION['survey']['curr_question']], $user_class_index, 1);
            $user_correctness = intval($q_class_char);
            $user_correctness = $user_correctness % (sizeof($user_sel_opt_name));
            $user_correctness = ($user_correctness == $_POST['arguments'][1]);
            if ($user_correctness) {
              $_SESSION['survey']['score'] += $user_sel_points;
            }
          }

          $eval_method = "ML";
          if ($_POST['arguments'][0] == 0) {
            $eval_method = "USER";
          }
          //Set the response from the user
          $response_val = array(
            'question#' => $_SESSION['survey']['curr_question'],
            'quest_name' => $_SESSION['exp_data'][$_SESSION['survey']['curr_question']],
            'correctness' => $ml_correctness,
            'eval_method' => $eval_method,
            'user_val' => $user_correctness
          );
          array_push($_SESSION['survey']['response'], $response_val);
        }

        //Increment question counter (how many user has completed)
        $_SESSION['survey']['curr_question']++;

        //If interventions are active and we've just completed the question count
        //required to trigger them, redirect to the intervention page
        if ($intervention_trigger && $_SESSION['survey']['curr_question'] == $intervention_count) {
          $result['intervention'] = true;
        }

        //If the user completed the total number of listed questions
        if ($_SESSION['survey']['curr_question'] == $num_questions) {
          $result['complete'] = true;
        }

        //Return the current survey data and question
        $result['survey'] = $_SESSION['survey'];
        $result['question_path'] = $img_source.$_SESSION['exp_data'][$_SESSION['survey']['curr_question']];
        $result['sel_options'] = $user_sel_opt_name;
        break;

      case 'file_demographics':
        $query = "UPDATE responses SET gender=?, age=? WHERE internal_identifier=?";
        $demo_stmt = $mysqli->stmt_init();
        $demo_stmt->prepare($query);
        $demo_stmt->bind_param("ssi", $_POST['arguments'][0], $_POST['arguments'][1], $_SESSION['internal_identifier']);
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
