<?php
  //The domain this experiement is hosted on
  $localhost_domain = "";
  //The domain this experiment will allow referral access from
  // $allowed_ext_refer = "worker.mturk.com";
  $allowed_ext_refer = "worker.mturk.com";

  //Database informaiton for mysqli access
  $db_name = "";
  $db_user = "";
  $db_pass = "";

  //Directory information for the images the classifier/user will evaluate
  $img_source = "./assets/img/trial/";
  //A and B is an evenly distributed split to ensure even correctness distribution
  //across pre- & post-intervention user selections
  $build_source_A = "./assets/img/build/A";
  $build_source_B = "./assets/img/build/B";
  $build_source_C = "./assets/img/build/C";
  $build_source_D = "./assets/img/build/D";
  $build_source_E = "./assets/img/build/E";
  $num_questions = 5;
  $images_per_question = 9;

  //Intervention config: whether/when to show. 0 = before start, 12 = before 13
  $intervention_trigger = false;
  $intervention_count = 999;

  //Number of total expected MTurk worker participants
  $total_mturk_runs = 100;

  //Max allowable survey time in seconds (3600 = 90 min)
  $max_allowed_time = 3600;
?>
