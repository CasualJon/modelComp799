<?php
  session_start();
  require 'control_variables.php';

  //Build an array with the unique image names (current total is 20)
  $exp_data = array();
  $dir_contents = scandir($img_source);

  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);

  //NOTE - Pick up here. Use RNG to pull out of dir_contents, prepend with the
  //value from img_source, adn add into exp_data

  $_SESSION['exp_data'] = $exp_data;


?>
