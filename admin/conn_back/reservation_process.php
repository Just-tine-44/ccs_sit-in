<?php
// filepath: c:\xampp\htdocs\login\admin\conn_back\reservation_process.php
// Start output buffering to prevent unwanted output before JSON
ob_start();

// Improved error handling
ini_set('display_errors', 0); // Don't output errors to browser
error_reporting(E_ALL); // But still log them to the error log
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log'); // Local error log file

// Start session and include necessary connections/functions
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Handle AJAX check for new reservations
if (isset($_GET['check_new']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    // Clean output buffer
    ob_clean();
    
    // Set proper content type
    header('Content-Type: application/json');
    
    $count_query = "SELECT COUNT(*) as count FROM reservations WHERE status = 'pending'";
    $count_result = $conn->query($count_query);
    $count = $count_result->fetch_assoc()['count'];
    
    echo json_encode(['success' => true, 'count' => (int)$count]);
    exit;
}

// Handle AJAX request for computers in a specific lab
if (isset($_GET['ajax']) && isset($_GET['lab'])) {
    // Clean output buffer
    ob_clean();
    
    // Set proper content type
    header('Content-Type: application/json');
    
    $ajax_lab = $_GET['lab'];
    
    // Get computers for the selected lab
    $ajax_computers_query = "SELECT pc_number, status FROM lab_computers WHERE lab_room = ?";
    $ajax_stmt = $conn->prepare($ajax_computers_query);
    $ajax_stmt->bind_param("s", $ajax_lab);
    $ajax_stmt->execute();
    $ajax_result = $ajax_stmt->get_result();
    
    $ajax_computers = [];
    while ($row = $ajax_result->fetch_assoc()) {
        $status = $row['status'];
        // Convert unknown to available
        if ($status === 'unknown' || empty($status)) {
            $status = 'available';
        }
        $ajax_computers[] = [
            'id' => $row['pc_number'],
            'status' => $status
        ];
    }
    
    // Add missing computers with "available" status
    for ($i = 1; $i <= 50; $i++) {
        $found = false;
        foreach ($ajax_computers as $pc) {
            if ($pc['id'] == $i) {
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $ajax_computers[] = [
                'id' => $i,
                'status' => 'available' // Changed from 'unknown' to 'available'
            ];
        }
    }
    
    // Sort computers numerically by ID before sending to client
    usort($ajax_computers, function($a, $b) {
        return (int)$a['id'] - (int)$b['id'];
    });
    
    // Count by status
    $ajax_available_count = 0;
    $ajax_used_count = 0;
    $ajax_maintenance_count = 0;
    $ajax_reserved_count = 0;
    
    foreach ($ajax_computers as $pc) {
        if ($pc['status'] == 'available') {
            $ajax_available_count++;
        } else if ($pc['status'] == 'used') {
            $ajax_used_count++;
        } else if ($pc['status'] == 'maintenance') {
            $ajax_maintenance_count++;
        } else if ($pc['status'] == 'reserved') {
            $ajax_reserved_count++;
        }
    }
    
    // Return as JSON
    echo json_encode([
        'success' => true, 
        'computers' => $ajax_computers,
        'available_count' => $ajax_available_count,
        'used_count' => $ajax_used_count,
        'maintenance_count' => $ajax_maintenance_count,
        'reserved_count' => $ajax_reserved_count
    ]);
    exit;
}

// Handle computer status toggle
if (isset($_POST['toggle_pc'])) {
    // Clean output buffer for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        ob_clean();
        header('Content-Type: application/json');
    }
    
    $pc_id = $_POST['pc_id'];
    $new_status = $_POST['new_status'];
    $lab_room = $_POST['lab_room'];
    
    // Check if computer exists
    $check_query = "SELECT * FROM lab_computers WHERE lab_room = ? AND pc_number = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $lab_room, $pc_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update existing computer
        $update_query = "UPDATE lab_computers SET status = ?, last_updated = NOW() WHERE lab_room = ? AND pc_number = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $new_status, $lab_room, $pc_id);
        $update_stmt->execute();
    } else {
        // Insert new computer
        $insert_query = "INSERT INTO lab_computers (lab_room, pc_number, status, last_updated) VALUES (?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sss", $lab_room, $pc_id, $new_status);
        $insert_stmt->execute();
    }
    
    // Add notification for status change
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        echo json_encode([
            'success' => true, 
            'status' => $new_status,
            'notification' => [
                'title' => 'PC Status Updated',
                'message' => 'PC ' . $pc_id . ' in Lab ' . $lab_room . ' is now ' . ucfirst($new_status),
                'type' => 'info'
            ]
        ]);
        exit;
    }
    
    // Redirect back to the current page for non-AJAX requests
    header("Location: ../admin_reservation.php?lab=" . $lab_room);
    exit;
}

// Handle reservation approval
if (isset($_POST['approve_request'])) {
    // Always clean output buffer and set content type for AJAX
    ob_clean();
    header('Content-Type: application/json');
    
    // Debug output
    error_log("REQUEST DATA: " . print_r($_POST, true));
    
    try {
        // Log session data for debugging
        error_log("SESSION DATA: " . print_r($_SESSION, true));
        
        // More robust request ID handling - use intval instead of filter_var for more reliability
        $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
        error_log("Processing request ID: " . $request_id . " (type: " . gettype($request_id) . ")");
        
        // Make sure the ID is valid
        if ($request_id <= 0) {
            error_log("Invalid request_id value: " . print_r($_POST['request_id'] ?? 'not set', true));
            throw new Exception("Invalid request ID: must be a positive number");
        }
        
        // Safely get admin ID from session
        $admin_id = null;
        
        // Check all possible session structures
        if (isset($_SESSION['admin']['id'])) {
            $admin_id = (int)$_SESSION['admin']['id'];
        } elseif (isset($_SESSION['admin_id'])) {
            $admin_id = (int)$_SESSION['admin_id'];
        } elseif (isset($_SESSION['admin']) && is_numeric($_SESSION['admin'])) {
            $admin_id = (int)$_SESSION['admin'];
        } elseif (isset($_SESSION['admin']) && is_string($_SESSION['admin'])) {
            // Try to get admin ID by username if admin is stored as string
            $admin_username = $_SESSION['admin'];
            $admin_query = "SELECT id FROM admin WHERE username = ?";
            $admin_stmt = $conn->prepare($admin_query);
            $admin_stmt->bind_param("s", $admin_username);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            if ($admin_result && $admin_row = $admin_result->fetch_assoc()) {
                $admin_id = (int)$admin_row['id'];
            } else {
                error_log("Admin username lookup failed for: " . $admin_username);
            }
        }
        
        // If admin ID not found, check if we can get it from database when username is in a nested array
        if (empty($admin_id) && isset($_SESSION['admin']['username'])) {
            $admin_username = $_SESSION['admin']['username'];
            $admin_query = "SELECT id FROM admin WHERE username = ?";
            $admin_stmt = $conn->prepare($admin_query);
            $admin_stmt->bind_param("s", $admin_username);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            if ($admin_result && $admin_row = $admin_result->fetch_assoc()) {
                $admin_id = (int)$admin_row['id'];
            } else {
                error_log("Admin username lookup failed for: " . $admin_username);
            }
        }
        
        // If still null, use admin ID 1 as fallback
        if (empty($admin_id)) {
            // Check if admin ID 1 exists
            $check_admin_query = "SELECT id FROM admin WHERE id = 1";
            $check_admin_result = $conn->query($check_admin_query);
            
            if ($check_admin_result && $check_admin_result->num_rows > 0) {
                $admin_id = 1; // Fallback to default admin ID
                error_log("WARNING: Using fallback admin ID=1. Session structure: " . print_r($_SESSION, true));
            } else {
                throw new Exception("Could not determine admin ID and default admin (ID 1) does not exist");
            }
        }
        
        error_log("Using Admin ID: " . $admin_id . " for approval");
        
        // Update the reservation status within a transaction
        $conn->begin_transaction();
        
        // First, get the user_id from the reservation to deduct their session credit
        $user_query = "SELECT user_id FROM reservations WHERE reservation_id = ?";
        $user_stmt = $conn->prepare($user_query);
        if (!$user_stmt) {
            throw new Exception("Failed to prepare user query: " . $conn->error);
        }
        
        $user_stmt->bind_param("i", $request_id);
        if (!$user_stmt->execute()) {
            throw new Exception("Failed to execute user query: " . $user_stmt->error);
        }
        
        $user_result = $user_stmt->get_result();
        if (!$user_result || $user_result->num_rows == 0) {
            throw new Exception("Could not find user ID for reservation: $request_id");
        }
        
        $user_id = $user_result->fetch_assoc()['user_id'];
        error_log("Retrieved user_id: $user_id for reservation: $request_id");
        
        // Check if the user has enough session credits
        $check_sessions_query = "SELECT session FROM stud_session WHERE id = ?";
        $check_sessions_stmt = $conn->prepare($check_sessions_query);
        $check_sessions_stmt->bind_param("i", $user_id);
        $check_sessions_stmt->execute();
        $check_sessions_result = $check_sessions_stmt->get_result();
        
        if (!$check_sessions_result || $check_sessions_result->num_rows == 0) {
            throw new Exception("User session record not found for user ID: $user_id");
        }
        
        $sessions = $check_sessions_result->fetch_assoc()['session'];
        if ($sessions <= 0) {
            throw new Exception("User has no remaining session credits");
        }
        
        // Deduct session credit when approving
        $deduct_query = "UPDATE stud_session SET session = session - 1 WHERE id = ? AND session > 0";
        $deduct_stmt = $conn->prepare($deduct_query);
        if (!$deduct_stmt) {
            throw new Exception("Failed to prepare session deduction statement: " . $conn->error);
        }
        
        $deduct_stmt->bind_param("i", $user_id);
        if (!$deduct_stmt->execute()) {
            throw new Exception("Failed to deduct session credit: " . $deduct_stmt->error);
        }
        
        if ($deduct_stmt->affected_rows == 0) {
            throw new Exception("Could not deduct session credit for user ID: $user_id");
        }
        
        error_log("Successfully deducted session credit for user ID: $user_id");
        
        // Now update the reservation status
        $approve_query = "UPDATE reservations SET status = 'approved', approved_by = ?, updated_at = NOW() WHERE reservation_id = ?";
        $approve_stmt = $conn->prepare($approve_query);
        if (!$approve_stmt) {
            throw new Exception("Failed to prepare approval statement: " . $conn->error);
        }
        
        $approve_stmt->bind_param("ii", $admin_id, $request_id);
        
        if (!$approve_stmt->execute()) {
            throw new Exception("Failed to execute approval: " . $approve_stmt->error);
        }
        
        if ($approve_stmt->affected_rows == 0) {
            throw new Exception("No reservation found with ID: $request_id");
        }
        
        // Get reservation details and check if it's for today or future
        $get_res_query = "SELECT r.lab_room, r.pc_number, r.reservation_date, r.time_in, u.firstname, u.lastname 
                          FROM reservations r 
                          JOIN users u ON r.user_id = u.id 
                          WHERE r.reservation_id = ?";
        $get_res_stmt = $conn->prepare($get_res_query);
        if (!$get_res_stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $get_res_stmt->bind_param("i", $request_id);
        if (!$get_res_stmt->execute()) {
            throw new Exception("Failed to execute query: " . $get_res_stmt->error);
        }

        $res_result = $get_res_stmt->get_result();

        if (!$res_result || $res_result->num_rows == 0) {
            throw new Exception("Could not find reservation data for ID: $request_id");
        }

        $res_data = $res_result->fetch_assoc();
        $lab_room = $res_data['lab_room'];
        $pc_number = $res_data['pc_number'];
        $reservation_date = $res_data['reservation_date'];
        $reservation_time = $res_data['time_in'];
        $student_name = $res_data['firstname'] . ' ' . $res_data['lastname'];

        error_log("Retrieved reservation data - Lab: $lab_room, PC: $pc_number, Date: $reservation_date, Time: $reservation_time");

        // Determine PC status based on when the reservation is for
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $reservation_datetime = $reservation_date . ' ' . $reservation_time;
        $current_datetime = $current_date . ' ' . $current_time;

        // If reservation is for today and started or within 15 minutes
        if ($reservation_date == $current_date && (strtotime($reservation_time) - strtotime($current_time) <= 900 || strtotime($reservation_time) <= strtotime($current_time))) {
            $pc_status = 'used'; // Mark as used if happening now or very soon
            error_log("Setting PC to USED - reservation is active or starting very soon");
        } else {
            // For future reservations
            $pc_status = 'reserved'; // New status specifically for future reservations
            error_log("Setting PC to RESERVED - reservation is for the future");
        }
        
        // First check if computer exists
        $check_pc_query = "SELECT * FROM lab_computers WHERE lab_room = ? AND pc_number = ?";
        $check_pc_stmt = $conn->prepare($check_pc_query);
        $check_pc_stmt->bind_param("ss", $lab_room, $pc_number);
        $check_pc_stmt->execute();
        $check_pc_result = $check_pc_stmt->get_result();
        
        if ($check_pc_result->num_rows > 0) {
            // Update existing computer with appropriate status
            $update_old_query = "UPDATE lab_computers SET status = ?, last_updated = NOW() WHERE lab_room = ? AND pc_number = ?";
            $update_old_stmt = $conn->prepare($update_old_query);
            $update_old_stmt->bind_param("sss", $pc_status, $lab_room, $pc_number);
            if (!$update_old_stmt->execute()) {
                throw new Exception("Failed to update PC status: " . $update_old_stmt->error);
            }
        } else {
            // Insert new computer with appropriate status
            $insert_pc_query = "INSERT INTO lab_computers (lab_room, pc_number, status, last_updated) VALUES (?, ?, ?, NOW())";
            $insert_pc_stmt = $conn->prepare($insert_pc_query);
            $insert_pc_stmt->bind_param("sss", $lab_room, $pc_number, $pc_status);
            if (!$insert_pc_stmt->execute()) {
                throw new Exception("Failed to insert PC: " . $insert_pc_stmt->error);
            }
        }
        
        // Create notification for the user - NEW CODE
        // First, check if notifications table exists, create it if not
        $check_table_sql = "SHOW TABLES LIKE 'notifications'";
        $table_exists = $conn->query($check_table_sql)->num_rows > 0;
        
        if (!$table_exists) {
            $create_table_sql = "CREATE TABLE notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                type VARCHAR(50) NOT NULL DEFAULT 'info',
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                related_id INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (user_id),
                INDEX (is_read)
            )";
            $conn->query($create_table_sql);
        }
        
        // Now create the notification
        $notification_query = "INSERT INTO notifications (user_id, title, message, type, related_id, created_at) 
                             VALUES (?, 'Reservation Approved', ?, 'success', ?, NOW())";
        $notification_stmt = $conn->prepare($notification_query);
        $message = "Your reservation for Lab $lab_room, PC $pc_number on $reservation_date at $reservation_time has been approved.";
        $notification_stmt->bind_param("isi", $user_id, $message, $request_id);
        
        if (!$notification_stmt->execute()) {
            error_log("Failed to create user notification: " . $notification_stmt->error);
            // Don't throw exception here, as this is not critical for the approval process
        }
        
        // Commit the transaction
        $conn->commit();
        
        // Log which admin approved the reservation
        $admin_username_query = "SELECT username FROM admin WHERE id = ?";
        $admin_username_stmt = $conn->prepare($admin_username_query);
        $admin_username_stmt->bind_param("i", $admin_id);
        $admin_username_stmt->execute();
        $admin_username_result = $admin_username_stmt->get_result();
        $admin_username = ($admin_username_result && $admin_username_result->num_rows > 0) ? 
                        $admin_username_result->fetch_assoc()['username'] : "Unknown";
        
        error_log("Approval successful for reservation #$request_id by admin: $admin_username (ID: $admin_id)");
        
        // Return notification data as part of the JSON response instead of a script tag
        echo json_encode([
            'success' => true, 
            'pc_status' => $pc_status, 
            'lab_room' => $lab_room,
            'pc_number' => $pc_number,
            'tooltipText' => 'Reserved for Future Use - This PC is booked for an upcoming reservation',
            'notification' => [
                'title' => 'Reservation Approved',
                'message' => 'You approved ' . $student_name . '\'s reservation for Lab ' . $lab_room . ', PC ' . $pc_number . '.',
                'type' => 'success',
                'relatedId' => $request_id
            ]
        ]);
        exit;
        
    } catch (Exception $e) {
        // Roll back transaction
        try {
            $conn->rollback();
        } catch (Exception $rollbackError) {
            error_log("Rollback failed: " . $rollbackError->getMessage());
        }
        
        error_log("Approval error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Handle reservation disapproval
if (isset($_POST['disapprove_request'])) {
    // Always clean output buffer and set content type for AJAX
    ob_clean();
    header('Content-Type: application/json');
    
    // Debug output
    error_log("REQUEST DATA (disapprove): " . print_r($_POST, true));
    
    try {
        // Log session data for debugging
        error_log("SESSION DATA: " . print_r($_SESSION, true));
        
        // Safely get request ID and reason - use intval instead of filter_var
        $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
        error_log("Processing disapproval request ID: " . $request_id . " (type: " . gettype($request_id) . ")");
        
        if ($request_id <= 0) {
            error_log("Invalid request_id value: " . print_r($_POST['request_id'] ?? 'not set', true));
            throw new Exception("Invalid request ID: must be a positive number");
        }
        
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
        if (empty($reason)) {
            throw new Exception("Disapproval reason is required");
        }
        
        // Safely get admin ID
        $admin_id = null;
        
        // Check all possible session structures
        if (isset($_SESSION['admin']['id'])) {
            $admin_id = (int)$_SESSION['admin']['id'];
        } elseif (isset($_SESSION['admin_id'])) {
            $admin_id = (int)$_SESSION['admin_id'];
        } elseif (isset($_SESSION['admin']) && is_numeric($_SESSION['admin'])) {
            $admin_id = (int)$_SESSION['admin'];
        } elseif (isset($_SESSION['admin']) && is_string($_SESSION['admin'])) {
            // Try to get admin ID by username if admin is stored as string
            $admin_username = $_SESSION['admin'];
            $admin_query = "SELECT id FROM admin WHERE username = ?";
            $admin_stmt = $conn->prepare($admin_query);
            $admin_stmt->bind_param("s", $admin_username);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            if ($admin_result && $admin_row = $admin_result->fetch_assoc()) {
                $admin_id = (int)$admin_row['id'];
            } else {
                error_log("Admin username lookup failed for: " . $admin_username);
            }
        }
        
        // If admin ID not found, check if we can get it from database when username is in a nested array
        if (empty($admin_id) && isset($_SESSION['admin']['username'])) {
            $admin_username = $_SESSION['admin']['username'];
            $admin_query = "SELECT id FROM admin WHERE username = ?";
            $admin_stmt = $conn->prepare($admin_query);
            $admin_stmt->bind_param("s", $admin_username);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            if ($admin_result && $admin_row = $admin_result->fetch_assoc()) {
                $admin_id = (int)$admin_row['id'];
            } else {
                error_log("Admin username lookup failed for: " . $admin_username);
            }
        }
        
        // If still null, use admin ID 1 as fallback
        if (empty($admin_id)) {
            // Check if admin ID 1 exists
            $check_admin_query = "SELECT id FROM admin WHERE id = 1";
            $check_admin_result = $conn->query($check_admin_query);
            
            if ($check_admin_result && $check_admin_result->num_rows > 0) {
                $admin_id = 1; // Fallback to default admin ID
                error_log("WARNING: Using fallback admin ID=1 for disapproval. Session structure: " . print_r($_SESSION, true));
            } else {
                throw new Exception("Could not determine admin ID and default admin (ID 1) does not exist");
            }
        }
        
        error_log("Using Admin ID: " . $admin_id . " for disapproval");
        
        // Begin transaction
        $conn->begin_transaction();
        
        // Get student name for notification
        $student_query = "SELECT r.user_id, u.firstname, u.lastname, r.lab_room, r.pc_number 
                          FROM reservations r 
                          JOIN users u ON r.user_id = u.id 
                          WHERE r.reservation_id = ?";
        $student_stmt = $conn->prepare($student_query);
        $student_stmt->bind_param("i", $request_id);
        $student_stmt->execute();
        $student_result = $student_stmt->get_result();
        $student_data = $student_result->fetch_assoc();
        $student_name = $student_data['firstname'] . ' ' . $student_data['lastname'];
        $lab_room = $student_data['lab_room'];
        $pc_number = $student_data['pc_number'];
        $user_id = $student_data['user_id'];
        
        // Update the reservation status
        $disapprove_query = "UPDATE reservations SET status = 'disapproved', disapproval_reason = ?, approved_by = ?, updated_at = NOW() WHERE reservation_id = ?";
        $disapprove_stmt = $conn->prepare($disapprove_query);
        if (!$disapprove_stmt) {
            throw new Exception("Failed to prepare disapproval statement: " . $conn->error);
        }
        
        $disapprove_stmt->bind_param("sii", $reason, $admin_id, $request_id);
        if (!$disapprove_stmt->execute()) {
            throw new Exception("Failed to execute disapproval: " . $disapprove_stmt->error);
        }
        
        if ($disapprove_stmt->affected_rows == 0) {
            throw new Exception("No reservation found with ID: $request_id");
        }
        
        // Create notification for the user about disapproval - NEW CODE
        // First, check if notifications table exists, create it if not
        $check_table_sql = "SHOW TABLES LIKE 'notifications'";
        $table_exists = $conn->query($check_table_sql)->num_rows > 0;
        
        if (!$table_exists) {
            $create_table_sql = "CREATE TABLE notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                type VARCHAR(50) NOT NULL DEFAULT 'info',
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                related_id INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (user_id),
                INDEX (is_read)
            )";
            $conn->query($create_table_sql);
        }
        
        // Now create the notification for disapproval
        $notification_query = "INSERT INTO notifications (user_id, title, message, type, related_id, created_at) 
                             VALUES (?, 'Reservation Disapproved', ?, 'warning', ?, NOW())";
        $notification_stmt = $conn->prepare($notification_query);
        $message = "Your reservation for Lab $lab_room, PC $pc_number has been disapproved. Reason: $reason";
        $notification_stmt->bind_param("isi", $user_id, $message, $request_id);
        
        if (!$notification_stmt->execute()) {
            error_log("Failed to create user notification for disapproval: " . $notification_stmt->error);
            // Don't throw exception, as this is not critical
        }
        
        // Commit the transaction
        $conn->commit();
        
        // Log which admin disapproved the reservation
        $admin_username_query = "SELECT username FROM admin WHERE id = ?";
        $admin_username_stmt = $conn->prepare($admin_username_query);
        $admin_username_stmt->bind_param("i", $admin_id);
        $admin_username_stmt->execute();
        $admin_username_result = $admin_username_stmt->get_result();
        $admin_username = ($admin_username_result && $admin_username_result->num_rows > 0) ? 
                          $admin_username_result->fetch_assoc()['username'] : "Unknown";
        
        error_log("Disapproval successful for reservation #$request_id by admin: $admin_username (ID: $admin_id) with reason: $reason");
        
        // Return notification data as part of the JSON response instead of a script tag
        echo json_encode([
            'success' => true,
            'notification' => [
                'title' => 'Reservation Disapproved',
                'message' => 'You disapproved ' . $student_name . '\'s reservation for Lab ' . $lab_room . ', PC ' . $pc_number . '. Reason: ' . $reason,
                'type' => 'warning',
                'relatedId' => $request_id
            ]
        ]);
        exit;
        
    } catch (Exception $e) {
        // Roll back transaction
        try {
            $conn->rollback();
        } catch (Exception $rollbackError) {
            error_log("Rollback failed: " . $rollbackError->getMessage());
        }
        
        error_log("Disapproval error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Get lab rooms from database
$lab_query = "SELECT DISTINCT lab_room FROM lab_computers ORDER BY lab_room";
$lab_result = $conn->query($lab_query);
$available_labs = ['524', '526', '528', '530', '542', '544', '517']; // Always include all labs

// Add any labs from database that might not be in the default list
if ($lab_result && $lab_result->num_rows > 0) {
    while ($row = $lab_result->fetch_assoc()) {
        if (!in_array($row['lab_room'], $available_labs)) {
            $available_labs[] = $row['lab_room'];
        }
    }
}

// Sort labs numerically
sort($available_labs);

// Selected lab room (default to first lab)
$selected_lab = isset($_GET['lab']) ? $_GET['lab'] : $available_labs[0];

// Get computers for the selected lab with their status
$computers_query = "SELECT pc_number, status FROM lab_computers WHERE lab_room = ?";
$stmt = $conn->prepare($computers_query);
$stmt->bind_param("s", $selected_lab);
$stmt->execute();
$result = $stmt->get_result();

$computers = [];
while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    // Convert 'unknown' to 'available'
    if ($status == 'unknown' || empty($status)) {
        $status = 'available';
    }
    $computers[$row['pc_number']] = [
        'id' => $row['pc_number'],
        'status' => $status
    ];
}

// Add missing computers with "available" status by default (not unknown)
for ($i = 1; $i <= 50; $i++) {
    if (!isset($computers[$i])) {
        $computers[$i] = [
            'id' => $i,
            'status' => 'available' 
        ];
    }
}

// Sort PC numbers numerically
uksort($computers, function($a, $b) {
    return (int)$a - (int)$b;
});

// Get pending reservation requests
$pending_query = "SELECT r.reservation_id as id, u.idno as student_id, 
                CONCAT(u.firstname, ' ', u.lastname) as student_name, 
                r.reservation_date as date, r.time_in as time, r.lab_room as lab, 
                r.pc_number as pc, r.purpose 
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                WHERE r.status = 'pending'
                ORDER BY r.reservation_date ASC, r.time_in ASC";
$pending_result = $conn->query($pending_query);

$pending_requests = [];
if ($pending_result) {
    while ($row = $pending_result->fetch_assoc()) {
        $pending_requests[] = $row;
    }
}

// Get reservation logs (approved and disapproved)
$logs_query = "SELECT r.reservation_id as id, u.idno as student_id, 
              CONCAT(u.firstname, ' ', u.lastname) as student_name, 
              r.reservation_date as date, r.time_in as time, r.lab_room as lab, 
              r.pc_number as pc, r.purpose, r.status, r.disapproval_reason as reason, 
              a.username as admin, r.updated_at as timestamp
              FROM reservations r
              JOIN users u ON r.user_id = u.id
              LEFT JOIN admin a ON r.approved_by = a.id
              WHERE r.status IN ('approved', 'disapproved')
              ORDER BY r.updated_at DESC
              LIMIT 50"; // Limit to most recent 50 logs
              
$logs_result = $conn->query($logs_query);

$reservation_logs = [];
if ($logs_result) {
    while ($row = $logs_result->fetch_assoc()) {
        $reservation_logs[] = $row;
    }
}

// Get current active sessions
$active_sessions_query = "SELECT r.reservation_id as id, u.idno as student_id, 
                        CONCAT(u.firstname, ' ', u.lastname) as student_name, 
                        r.lab_room as lab, r.pc_number as pc, r.purpose, 
                        r.time_in as start_time, r.time_out as end_time
                        FROM reservations r
                        JOIN users u ON r.user_id = u.id
                        WHERE r.status = 'approved' 
                        AND r.reservation_date = CURDATE()
                        AND (
                            (CAST(CURTIME() AS TIME) BETWEEN CAST(r.time_in AS TIME) AND CAST(COALESCE(r.time_out, ADDTIME(r.time_in, '02:00:00')) AS TIME)) 
                            OR (TIME_TO_SEC(TIMEDIFF(r.time_in, CURTIME())) <= 900) -- Include sessions starting in next 15 minutes
                        )
                        ORDER BY r.time_in";
                        
$active_sessions_result = $conn->query($active_sessions_query);

$active_sessions = [];
if ($active_sessions_result) {
    while ($row = $active_sessions_result->fetch_assoc()) {
        $active_sessions[] = $row;
    }
}

// Stats for filter buttons
$total_computers = count($computers);
$available_count = 0;
$used_count = 0;
$reserved_count = 0;
// Keep maintenance_count for legacy data but don't display it
$maintenance_count = 0;

foreach ($computers as $pc) {
    if ($pc['status'] == 'available') {
        $available_count++;
    } else if ($pc['status'] == 'used') {
        $used_count++;
    } else if ($pc['status'] == 'reserved') {
        $reserved_count++;
    } else if ($pc['status'] == 'maintenance') {
        // Count maintenance but convert them to "used" when selecting them
        $maintenance_count++;
    }
}
?>