<?php
  session_start();
  require './php_includes/db.php';

  //Here is where we will query the user for the admin password

  $uid = 799;
  $password = "steamboatwillie";
  $pw_encrypted = password_hash($password, PASSWORD_DEFAULT);
  $set_admin = $mysqli->prepare("INSERT INTO admin_config (id, password) VALUES (?, ?)");
  $set_admin->bind_param("is", $uid, $pw_encrypted);
  $set_admin->execute();
  $set_admin->close();

  // $query = "SELECT * FROM admin_config WHERE id=?";
  // $admin_stmt = $mysqli->stmt_init();
  // $admin_stmt->prepare($query);
  // $admin_stmt->bind_param("i", $uid);
  // $admin_stmt->execute();
  // $resultSet = $admin_stmt->get_result();
  // $admin_stmt->close();
  //
  // if ($resultSet->num_rows == 0) {
  //   unset($uid, $query);
  //   $resultSet->free();
  //   $_SESSION['message'] = "Something went wrong... no data was returned in querying admin credentails.";
  //   header("location: ./error.php");
  //   exit;
  // }
  //
  // $admin_data = $resultSet->fetch_assoc();
  // $resultSet->free();
  // if ($_POST['id'] === $admin_data['id']) {
  //   if (password_verify($_POST['password'], $admin_data['password'])) {
  //     $_SESSION['admin'] = 1;
  //   }
  // }
  // unset($admin_data);
?>
