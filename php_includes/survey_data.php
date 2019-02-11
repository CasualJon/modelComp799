<?php
  session_start();
  require 'control_variables.php';

  //Build an array with the unique image names (current total is 20)
  $exp_data = array();
  $dir_contents = scandir($img_source);

  //Remove the current and parent directories from the array...
  if (($key = array_search(".", $dir_contents)) !== false) {
    unset($dir_contents[$key]);
  }
  if (($key = array_search("..", $dir_contents)) !== false) {
    unset($dir_contents[$key]);
  }
  unset($key);
  
  $_SESSION['exp_data'] = $dir_contents;


?>
