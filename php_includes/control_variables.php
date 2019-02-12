<?php
  //The domain this experiement is hosted on
  $localhost_domain = "quickfeedback.io";
  //The domain this experiment will allow referral access from
  $allowed_ext_refer = "mturk.com";

  //Database informaiton for mysqli access
  $db_name = "model_understanding_spring19";
  $db_user = "phpmyadmin";
  $db_pass = "cs799";

  //Directory information for the images the classifier/user will evaluate
  $img_source = "./assets/img/lyrics/";
  $num_questions = 24;

  //Intervention config: whether/when to show. 0 = before start, 12 = before 13
  $intervention_trigger = true;
  $intervention_count = 12;
?>
