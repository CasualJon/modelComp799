<?php
  //The domain this experiement is hosted on
  $localhost_domain = "quickfeedback.io";
  //The domain this experiment will allow referral access from
  $allowed_ext_refer = "mturk.com";

  //Database informaiton for mysqli access
  $db_name = "model_understanding_spring19";
  $db_user = "phpmyadmin";
  $db_pass = "westdayton";

  //Directory information for the images the classifier/user will evaluate
  $img_source = "./assets/img/lyrics/";
  $num_questions = 4;

  //Which char index in the string of the question/image name inicates T/F?
  //If image name is 448A.png, index 2 == 8... Even == false, Odd == true
  $indicator_index = 2;
  $model_sel_points = 4;
  $user_sel_points = 3;

  //Intervention config: whether/when to show. 0 = before start, 12 = before 13
  $intervention_trigger = true;
  $intervention_count = 12;

  //Max allowable survey time in seconds (5400 = 90 min)
  $max_allowed_time = 5400;
?>
