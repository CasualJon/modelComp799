<?php
  session_start();
  require './db.php';

  //Here we will query the user for the admin password
  $query = "SELECT * FROM admin_config WHERE id=?";
  $admin_stmt = $mysqli->stmt_init();
  $admin_stmt->prepare($query);
  $admin_stmt->bind_param("i", $_POST['uid']);
  $admin_stmt->execute();
  $resultSet = $admin_stmt->get_result();
  $admin_stmt->close();

  if ($resultSet->num_rows == 0) {
    unset($uid, $query);
    $resultSet->free();
    $_SESSION['message'] = "Admin credentials failed: 43.";
    header("location: ../error.php");
    exit;
  }

  $admin_data = $resultSet->fetch_assoc();
  $resultSet->free();
  //Here (for now) b/c private repo and PHP not displayed: 'westdayton'
  if (password_verify($_POST['password'], $admin_data['password'])) {
    $_SESSION['admin'] = 1;
    header("location: ../index.php");
  }
  else {
    $_SESSION['message'] = "Admin credentials failed: 44. ";
    header("location: ../error.php");
  }
  unset($admin_data);
?>
