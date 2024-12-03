<?php 
require_once 'db_con.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_login'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if (isset($_POST['add_user'])) {
    // Sanitize and retrieve form inputs
    $name = mysqli_real_escape_string($db_con, $_POST['name']);
    $username = mysqli_real_escape_string($db_con, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password for security
    $status = mysqli_real_escape_string($db_con, $_POST['status']);

    // Handle photo upload
    $photo = $_FILES['photo']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($photo);
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // Insert new user into the database
        $query = "INSERT INTO users (name, username, password, photo, status) VALUES ('$name', '$username', '$password', '$photo', '$status')";
        
        if (mysqli_query($db_con, $query)) {
            echo "<script>alert('User added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding user.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading photo.');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add User</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New User</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control-file" id="photo" name="photo" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        </form>
    </div>

    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
