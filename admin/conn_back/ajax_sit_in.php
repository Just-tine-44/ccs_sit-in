<?php
// filepath: c:\xampp\htdocs\login\admin\conn_back\ajax_sit_in.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// ========== DIRECT SIT-IN TIMEOUT FUNCTIONALITY ==========
// This matches the original functionality from sit-in_process.php but with AJAX
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
        
        // Start transaction to ensure both operations succeed or fail together
        $conn->begin_transaction();
        
        try {
            // 1. Update sit-in record to mark as completed (using NOW() for consistency with original code)
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
            
            // Format current time for display
            $formatted_time = date('g:i A');
            
            // Get check-in and check-out times for duration calculation
            $getTimesQuery = "SELECT check_in_time, check_out_time FROM curr_sit_in WHERE sit_in_id = ?";
            $timesStmt = $conn->prepare($getTimesQuery);
            $timesStmt->bind_param("i", $sit_in_id);
            $timesStmt->execute();
            $timesResult = $timesStmt->get_result();
            $times = $timesResult->fetch_assoc();
            
            // Calculate session duration
            $check_in = new DateTime($times['check_in_time']);
            $check_out = new DateTime($times['check_out_time']);
            $interval = $check_in->diff($check_out);
            $duration_formatted = $interval->format('%h hr %i min');
            
            echo json_encode([
                'success' => true, 
                'message' => 'Student checked out and session count updated successfully',
                'duration' => $duration_formatted,
                'end_time' => $formatted_time
            ]);
        } catch (Exception $e) {
            // If any error occurs, rollback the transaction
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Sit-in record not found']);
    }
    exit();
}

// ========== RESERVATION END SESSION FUNCTIONALITY ==========
// This handles the "End Session" button in the reservation tab
if (isset($_POST['checkout_reservation']) && isset($_POST['reservation_id'])) {
    $reservation_id = intval($_POST['reservation_id']);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get reservation details
        $getDetailsQuery = "SELECT r.lab_room, r.pc_number, r.time_in, r.user_id,
                           u.firstname, u.lastname 
                           FROM reservations r
                           JOIN users u ON r.user_id = u.id
                           WHERE r.reservation_id = ?";
        $detailsStmt = $conn->prepare($getDetailsQuery);
        $detailsStmt->bind_param("i", $reservation_id);
        $detailsStmt->execute();
        $detailsResult = $detailsStmt->get_result();
        
        if ($detailsResult->num_rows > 0) {
            $reservationDetails = $detailsResult->fetch_assoc();
            $lab_room = $reservationDetails['lab_room'];
            $pc_number = $reservationDetails['pc_number'];
            $student_name = $reservationDetails['firstname'] . ' ' . $reservationDetails['lastname'];
            
            // Get current time - this is the actual end time
            $current_time = date('Y-m-d H:i:s');
            $formatted_time = date('g:i A', strtotime($current_time));
            
            // Mark reservation as completed and set time_out to exact current time
            $completeQuery = "UPDATE reservations SET status = 'completed', time_out = ?, updated_at = NOW() WHERE reservation_id = ?";
            $completeStmt = $conn->prepare($completeQuery);
            $completeStmt->bind_param("si", $current_time, $reservation_id);
            $completeStmt->execute();
            
            // Update PC status to available
            $updatePCQuery = "UPDATE lab_computers SET status = 'available' WHERE lab_room = ? AND pc_number = ?";
            $updatePCStmt = $conn->prepare($updatePCQuery);
            $updatePCStmt->bind_param("ss", $lab_room, $pc_number);
            $updatePCStmt->execute();
            
            $conn->commit();
            
            // For reservations, only include end time (no duration calculation)
            echo json_encode([
                'success' => true, 
                'message' => 'Reservation session ended successfully',
                'end_time' => $formatted_time,
                'student_name' => $student_name
            ]);
        } else {
            throw new Exception("Reservation not found");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error ending reservation: ' . $e->getMessage()]);
    }
    exit();
}

// Default response for invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid request']);