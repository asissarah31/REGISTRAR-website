<?php
// Check if a session has already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already started
}

// Check if user is logged in
if (isset($_SESSION['user_login'])) {
    // Decode the ID and photo parameters from URL
    $id = base64_decode($_GET['id']);
    $photo = base64_decode($_GET['photo']);

    // Prepare and execute the query to delete the student record from the database
    $delete_query = "DELETE FROM student_info WHERE id = ?";
    if ($stmt = mysqli_prepare($db_con, $delete_query)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        
        // Check if the student record was deleted
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $file_path = 'images/' . $photo;

          
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        header('Location: index.php?page=all-student&delete=error&reason=db_query_failed');
    }
} else {
    header('Location: login.php');
}
?>
