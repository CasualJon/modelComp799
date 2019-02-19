<?php
  session_start();
  require 'control_variables.php';

  //Build an array with the unique image names (current total is 20)
  $exp_data = array();
  $build = [$build_source_A, $build_source_B];
  $option = mt_rand(0, 999) % 2;

  $dir_contents = scandir($build[$option]);

  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);

  //Set number of remaining questions equal to the total number of questions
  $q_remaining = $num_questions / 2;
  //Randomly select questions from dir_contents to place into exp_data (to randomize order)
  for ($i = 0; $i < $num_questions / 2; $i++) {
    $tmp = $q_remaining - 1;
    $indx = mt_rand(0, $tmp);
    array_push($exp_data, $dir_contents[$indx]);
    array_splice($dir_contents, $indx, 1);
    $q_remaining--;
  }

  $option = ($option + 1) % 2;
  $dir_contents = scandir($build[$option]);

  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);

  //Set number of remaining questions equal to the total number of questions
  $q_remaining = $num_questions / 2;
  //Randomly select questions from dir_contents to place into exp_data (to randomize order)
  for ($i = 0; $i < $num_questions / 2; $i++) {
    $tmp = $q_remaining - 1;
    $indx = mt_rand(0, $tmp);
    array_push($exp_data, $dir_contents[$indx]);
    array_splice($dir_contents, $indx, 1);
    $q_remaining--;
  }

  $_SESSION['exp_data'] = $exp_data;
?>
