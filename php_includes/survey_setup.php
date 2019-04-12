<?php
  session_start();
  require 'control_variables.php';

  //Build an array with the unique image names (current total is 2)
  $exp_data = array();
  $build = [$build_source_A, $build_source_B];
  // $option = mt_rand(0, 999) % 2;

  //Hard coding which build directory gets used in order to equalize number of groups
  $option = 0;

  //Scan the build directory to set the pre-intervention images
  $dir_contents = scandir($build[$option]);
  //Set matrix_directory to enable intervention confusion matrix
  $_SESSION['matrix_directory'] = $build[$option];

  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);

  //Set number of remaining questions equal to the total number of images for this question
  $remaining = $images_per_question;
  //Randomly select questions from dir_contents to place into exp_data (to randomize order)
  for ($i = 0; $i < $images_per_question; $i++) {
    $tmp = $remaining - 1;
    $indx = mt_rand(0, $tmp);
    array_push($exp_data, $dir_contents[$indx]);
    array_splice($dir_contents, $indx, 1);
    $remaining--;
  }

  $option = ($option + 1) % 2;
  $dir_contents = scandir($build[$option]);

  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);

  //Set number of remaining questions equal to the total number of images for this question
  $remaining = $images_per_question;
  //Randomly select questions from dir_contents to place into exp_data (to randomize order)
  for ($i = 0; $i < $images_per_question; $i++) {
    $tmp = $remaining - 1;
    $indx = mt_rand(0, $tmp);
    array_push($exp_data, $dir_contents[$indx]);
    array_splice($dir_contents, $indx, 1);
    $remaining--;
  }

  $_SESSION['exp_data'] = $exp_data;
?>
