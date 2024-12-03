<?php
require_once 'db_con.php';

if (isset($_GET['filename'])) {
    $filename = basename($_GET['filename']);
    $filepath = 'uploads/' . $filename; // Adjust path as necessary

    if (file_exists($filepath)) {
        // Set headers to initiate download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        
        // Read the file and send to output
        readfile($filepath);
        exit;
    } else {
        echo 'File not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
