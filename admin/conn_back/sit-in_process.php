<?php
// filepath: c:\xampp\htdocs\login\admin\conn_back\sit-in_process.php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// ==========================================
// DIRECT SIT-IN CHECKOUT (NON-AJAX FALLBACK)
// ==========================================
// This handles form submissions for direct sit-ins when JavaScript is disabled
if (isset($_POST['checkout']) && isset($_POST['sit_in_id'])) {
    $sit_in_id = $_POST['sit_in_id'];
    
    // Get the sit-in record to update
    $query = "SELECT * FROM curr_sit_in WHERE sit_in_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sit_in_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $sit_in = $result->fetch_assoc();
        $user_id = $sit_in['user_id'];
        $check_in_time = $sit_in['check_in_time'];
        
        // Start transaction to ensure both operations succeed or fail together
        $conn->begin_transaction();
        
        try {
            // Calculate the session duration for display in message
            $now = new DateTime();
            $check_in = new DateTime($check_in_time);
            $interval = $check_in->diff($now);
            $duration = $interval->format('%h hr %i min');
            
            // 1. Update sit-in record to mark as completed with current time
            $updateQuery = "UPDATE curr_sit_in SET check_out_time = NOW(), status = 'completed' WHERE sit_in_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $sit_in_id);
            $updateStmt->execute();
            
            // 2. Decrement the session count
            $decrementQuery = "UPDATE stud_session SET session = session - 1 WHERE id = ? AND session > 0";
            $decrementStmt = $conn->prepare($decrementQuery);
            $decrementStmt->bind_param("i", $user_id);
            $decrementStmt->execute();
            
            // If everything succeeds, commit the transaction
            $conn->commit();
            
            $_SESSION['message'] = "Student checked out successfully. Session duration: " . $duration;
            $_SESSION['msg_type'] = "success";
        } catch (Exception $e) {
            // If any error occurs, rollback the transaction
            $conn->rollback();
            
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['msg_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Sit-in record not found";
        $_SESSION['msg_type'] = "error";
    }
    
    // Redirect to prevent form resubmission
    header("Location: ../sit_in.php");
    exit();
}

// ==============================================
// RESERVATION END SESSION (NON-AJAX FALLBACK)
// ==============================================
// This handles form submissions for reservations when JavaScript is disabled
if (isset($_POST['checkout_reservation']) && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get reservation details
        $getDetailsQuery = "SELECT lab_room, pc_number, user_id FROM reservations WHERE reservation_id = ?";
        $detailsStmt = $conn->prepare($getDetailsQuery);
        $detailsStmt->bind_param("i", $reservation_id);
        $detailsStmt->execute();
        $detailsResult = $detailsStmt->get_result();
        
        if ($detailsResult->num_rows > 0) {
            $reservationDetails = $detailsResult->fetch_assoc();
            $lab_room = $reservationDetails['lab_room'];
            $pc_number = $reservationDetails['pc_number'];
            $user_id = $reservationDetails['user_id'];
            
            // Current time for the end time display
            $current_time = date('g:i A');
            
            // Mark reservation as completed and set time_out to current time
            $completeQuery = "UPDATE reservations SET status = 'completed', time_out = NOW(), updated_at = NOW() WHERE reservation_id = ?";
            $completeStmt = $conn->prepare($completeQuery);
            $completeStmt->bind_param("i", $reservation_id);
            $completeStmt->execute();
            
            // Update PC status to available
            $updatePCQuery = "UPDATE lab_computers SET status = 'available' WHERE lab_room = ? AND pc_number = ?";
            $updatePCStmt = $conn->prepare($updatePCQuery);
            $updatePCStmt->bind_param("ss", $lab_room, $pc_number);
            $updatePCStmt->execute();
            
            $conn->commit();
            
            $_SESSION['message'] = "Reservation session ended successfully at " . $current_time;
            $_SESSION['msg_type'] = "success";
        } else {
            throw new Exception("Reservation not found");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error ending reservation: " . $e->getMessage();
        $_SESSION['msg_type'] = "error";
    }
    
    // Redirect to prevent form resubmission
    header("Location: ../sit_in.php");
    exit();
}

// ==============================================
// DATA RETRIEVAL FOR DISPLAY
// ==============================================

// Get all active direct sit-in sessions with student info
$query = "SELECT s.sit_in_id, s.user_id, s.laboratory, s.purpose, s.check_in_time, 
                 u.idno, u.firstname, u.midname, u.lastname, u.course, u.level, 
                 ss.session as remaining_sessions
          FROM curr_sit_in s
          JOIN users u ON s.user_id = u.id
          JOIN stud_session ss ON u.id = ss.id
          WHERE s.status = 'active'
          ORDER BY s.check_in_time DESC";
$result = $conn->query($query);

// Get all reservation-based sessions (both active and upcoming)
$reservationQuery = "SELECT r.reservation_id, r.lab_room, r.pc_number, r.purpose, 
                    r.time_in, r.time_out, r.reservation_date, r.status,
                    u.id as user_id, u.idno, u.firstname, u.midname, u.lastname, u.course, u.level
                    FROM reservations r
                    JOIN users u ON r.user_id = u.id
                    WHERE r.status = 'approved' 
                    AND r.reservation_date >= CURDATE() 
                    ORDER BY r.reservation_date ASC, r.time_in ASC";
$reservationResult = $conn->query($reservationQuery);

// ==============================================
// ADDITIONAL STATUS INFO FOR DISPLAY
// ==============================================

// Add additional status information for reservations (active or upcoming)
if ($reservationResult && $reservationResult->num_rows > 0) {
    $reservationData = [];
    while ($row = $reservationResult->fetch_assoc()) {
        // Add a status indicator (active or upcoming)
        $current_time = date('H:i:s');
        $current_date = date('Y-m-d');
        $start_time = $row['time_in'];
        $res_date = $row['reservation_date'];
        
        // Check if reservation is for today and has started
        if ($current_date == $res_date && $current_time >= $start_time) {
            $row['session_status'] = 'active';
        } else {
            $row['session_status'] = 'upcoming';
        }
        
        $reservationData[] = $row;
    }
    
    // Reset the result to beginning so it can be used in sit_in.php
    $reservationResult = $conn->query($reservationQuery);
}
?>