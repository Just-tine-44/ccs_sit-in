<?php
// filepath: c:\xampp\htdocs\login\admin\conn_back\new_admin.php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../../conn/dbcon.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_admin'])) {
    // Get form data
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password']; // Plain text password
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $new_username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Username already exists
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Username already exists',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '../admin_login.php?add_admin=true';
                    });
                });
              </script>";
    } else {
        // Insert new admin with plain text password
        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $new_username, $new_password);
        
        if ($stmt->execute()) {
            // Success
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Admin account created successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '../admin_login.php';
                        });
                    });
                  </script>";
        } else {
            // Error
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Error creating admin account',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '../admin_login.php?add_admin=true';
                        });
                    });
                  </script>";
        }
    }
} else {
    // Redirect if accessed directly
    header("Location: ../admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating Admin...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- This page is just for processing the form -->
</body>
</html>