<?php
  session_start();
  require 'control_variables.php';

  //Build an array with the unique image names (current total is 20)
  $exp_data = array();
  $dir_contents = scandir($img_source);

  $_SESSION['exp_data'] = $dir_contents;


?>
