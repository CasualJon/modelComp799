<?php
  session_start();
  require 'control_variables.php';

  //Build an array with the unique image names (current total is 20)
  $exp_data = array();
  $dir_contents = scandir($img_source);

  //Remove the current and parent directories from the array...
  // if (($key = array_search(".", $dir_contents)) !== false) {
  //   unset($dir_contents[$key]);
  // }
  // if (($key = array_search("..", $dir_contents)) !== false) {
  //   unset($dir_contents[$key]);
  // }
  // unset($key);
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);

  $_SESSION['exp_data'] = $dir_contents;


?>
