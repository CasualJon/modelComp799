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
          //Get the correctness of the response
          $q_correct_char = substr($_SESSION['exp_data'][$_SESSION['survey']['curr_question']], $indicator_index, 1);
          $correctness = intval($q_correct_char);
          //Mod 2 to reduce... 0 == false, 1 == true
          $correctness = $correctness % 2;

          //If ML scored (arguments[0] == 1), udpate score with correctness
          if ($_POST['arguments'][0] == 1 && $correctness) {
            $_SESSION['survey']['score'] += $model_sel_points;
          }
          //If user scored (arguments[0] == 0), update score if their choice matches
          if ($_POST['arguments'][0] == 0 && $_POST['arguments'][1] == $correctness) {
            $_SESSION['survey']['score'] += $user_sel_points;
          }

          $eval_method = "ML";
          $user_val = NULL;
          if ($_POST['arguments'][0] == 0) {
            $eval_method = "USER";
            $user_val = $_POST['arguments'][1];
          }
          //Set the response from the user
          $response_val = array(
            'question#' => $_SESSION['survey']['curr_question'],
            'quest_name' => $_SESSION['exp_data'][$_SESSION['survey']['curr_question']],
            'correctness' => $correctness,
            'eval_method' => $eval_method,
            'user_val' => $user_val
          );
          array_push($_SESSION['survey']['response'], $response_val);
        }

        //If interventions are active and we've just completed the question count
        //required to trigger them, redirect to the intervention page
        if ($intervention_trigger && $_SESSION['survey']['curr_question'] == $intervention_count) {
          header("location: ../intervention.php");
          exit;
        }

        //If the user completed the total number of listed questions, then
        //redirect to the thank you page
        if ($_SESSION['survey']['curr_question'] == $num_questions) {
          header("location: ../thank_you.php");
          exit;
        }

        //Increment question counter
        $_SESSION['survey']['curr_question']++;

        //Return the current survey data and question
        $result['survey'] = $_SESSION['survey'];
        $result['question_path'] = $img_source.$_SESSION['exp_data'][$_SESSION['survey']['curr_question']];
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
