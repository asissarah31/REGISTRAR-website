<?php 
require_once 'db_con.php';
session_start();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch current status of the user
    $query = mysqli_query($db_con, "SELECT * FROM `users` WHERE `id` = '$user_id'");
    $user = mysqli_fetch_array($query);

    if (isset($_POST['update_status'])) {
        $new_status = mysqli_real_escape_string($db_con, $_POST['status']);
        
        // Update the status
        $update_query = "UPDATE `users` SET `status` = '$new_status' WHERE `id` = '$user_id'";
        $result = mysqli_query($db_con, $update_query);
        
        if ($result) {
            header("Location: index.php?page=all_users&status=updated");
        } else {
            echo "Failed to update status!";
        }
    }
} else {
    header("Location: index.php?page=all_users");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Status</title>
</head>
<body>
    <h2>Edit Status for <?php echo $user['name']; ?></h2>

    <form action="" method="POST">
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="active" <?php if ($user['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if ($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
    </form>
</body>
</html>
