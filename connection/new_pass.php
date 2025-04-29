<?php
session_start();
include '../conn/dbcon.php'; 

// Get user data from session - handling both possible structures
if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['idno'])) {
    // Use the entire user array if available
    $user = $_SESSION['user'];
    $idno = $user['idno'];
} elseif (isset($_SESSION['stud_session']) && isset($_SESSION['stud_session']['idno'])) {
    // Extract ID from stud_session and query DB
    $idno = $_SESSION['stud_session']['idno'];
    
    // Get user details from database
    $query = "SELECT * FROM users WHERE idno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // User not found in database
        header("Location: login.php?error=invalid_user");
        exit();
    }
} else {
    // No valid session found
    header("Location: login.php");
    exit();
}

// Check for flash messages in session
$message = "";
$alertType = "";

if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $alertType = $_SESSION['flash_alert_type'];
    
    // Clear the flash message after retrieving it
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_alert_type']);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if new passwords match
    if ($new_password === $confirm_password) {
        // Check password strength
        if (strlen($new_password) >= 8) {
            // Hash new password - commented out as requested
            // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // 
            // // Update password in database
            // $update_query = "UPDATE users SET password = ? WHERE idno = ?";
            // $update_stmt = $conn->prepare($update_query);
            // $update_stmt->bind_param("ss", $hashed_password, $idno);

            // Store password as plain text (as requested, though not recommended)
            $plain_password = $new_password; // No hashing

            // Update password in database
            $update_query = "UPDATE users SET password = ? WHERE idno = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $plain_password, $idno);
            
            if ($update_stmt->execute()) {
                // Also update session if we're storing the full user object there
                if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
                    $_SESSION['user']['password'] = $plain_password;
                }
                
                // Set flash message for the next page load
                $_SESSION['flash_message'] = "Password updated successfully!";
                $_SESSION['flash_alert_type'] = "success";
                
                header("Location: password_edit.php");
                exit();
            } else {
                $message = "Error updating password. Please try again.";
                $alertType = "error";
            }
        } else {
            $message = "Password must be at least 8 characters long.";
            $alertType = "error";
        }
    } else {
        $message = "New passwords do not match.";
        $alertType = "error";
    }
}

// Mask the password for display (or comment out to show raw password)
$displayPassword = $user['password'];
if (strlen($displayPassword) > 4) {
    $displayPassword = substr($displayPassword, 0, 3) . str_repeat('*', 6);
}
?>