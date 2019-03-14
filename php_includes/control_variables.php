<?php
  //The domain this experiement is hosted on
  $localhost_domain = "quickfeedback.io";
  //The domain this experiment will allow referral access from
  // $allowed_ext_refer = "worker.mturk.com";
  $allowed_ext_refer = "worker.mturk.com";

  //Database informaiton for mysqli access
  $db_name = "model_understanding_spring19";
  $db_user = "phpmyadmin";
  $db_pass = "westdayton";

  //Directory information for the images the classifier/user will evaluate
  $img_source = "./assets/img/pets/";
  //A and B is an evenly distributed split to ensure even correctness distribution
  //across pre- & post-intervention user selections
  $build_source_A = "./assets/img/build/A";
  $build_source_B = "./assets/img/build/B";
  $num_questions = 24;

  //Intervention config: whether/when to show. 0 = before start, 12 = before 13
  $intervention_trigger = true;
  $intervention_count = 12;

  //Which char index in the string of the question/image name inicates T/F?
  //If image name is 448A.png, index 2 == 8... Even == false, Odd == true
  $user_class_index = 1;
  $ml_indicator_index = 2;
  $model_sel_points = 3;
  $user_sel_points = 2;
  $user_sel_opt_name = array("Dog", "Cat");

  //Max allowable survey time in seconds (3600 = 90 min)
  $max_allowed_time = 3600;
?>
