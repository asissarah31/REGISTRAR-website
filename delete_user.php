<?php
require_once 'db_con.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'];

  // Delete user query
  $delete_query = "DELETE FROM users WHERE id = '$user_id'";
  if (mysqli_query($db_con, $delete_query)) {
    echo 'success';
  } else {
    echo 'error';
  }
}
?>
