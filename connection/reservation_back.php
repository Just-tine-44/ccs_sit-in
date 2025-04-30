<?php
// Start session and include necessary connections/functions
session_start();
include('../conn/dbcon.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get remaining sessions from stud_session table
$sql_sessions = "SELECT session FROM stud_session WHERE id = ?";
$stmt_sessions = $conn->prepare($sql_sessions);
$stmt_sessions->bind_param("i", $user_id);
$stmt_sessions->execute();
$result_sessions = $stmt_sessions->get_result();

$remaining_sessions = 0;
if ($result_sessions->num_rows > 0) {
    $sessions_data = $result_sessions->fetch_assoc();
    $remaining_sessions = $sessions_data['session'];
}

// First define your default labs
$default_labs = ['524', '526', '528', '530', '542', '544', '517'];

// Try to get labs from database
$sql_labs = "SELECT DISTINCT lab_room FROM lab_computers ORDER BY lab_room";
$result_labs = $conn->query($sql_labs);

// Use database results if they exist, otherwise use defaults
$available_labs = [];
if ($result_labs && $result_labs->num_rows > 0) {
    while ($row = $result_labs->fetch_assoc()) {
        $available_labs[] = $row['lab_room'];
    }
    
    // Add any default labs that aren't already in the list
    foreach ($default_labs as $lab) {
        if (!in_array($lab, $available_labs)) {
            $available_labs[] = $lab;
        }
    }
} else {
    // No labs in database, use defaults
    $available_labs = $default_labs;
}

// Sort the labs numerically
sort($available_labs);

// AJAX handler for reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    $response = ['success' => false, 'message' => ''];
    
    // Get form data
    $purpose = $_POST['purpose'] ?? '';
    $lab_room = $_POST['labRoom'] ?? '';
    $pc_number = $_POST['pcNumber'] ?? '';
    $date = $_POST['date'] ?? '';
    $time_in = $_POST['timeIn'] ?? '';
    
    // Basic validation
    if (empty($purpose) || empty($lab_room) || empty($pc_number) || empty($date) || empty($time_in)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }
    
    try {
        // Begin transaction
        $conn->begin_transaction();
        
        // Insert reservation - MODIFIED: No session deduction on reservation creation
        $sql = "INSERT INTO reservations (user_id, lab_room, pc_number, purpose, reservation_date, time_in) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $user_id, $lab_room, $pc_number, $purpose, $date, $time_in);
        
        if ($stmt->execute()) {
            // Get the new reservation ID
            $reservation_id = $conn->insert_id;
            
            // Commit transaction
            $conn->commit();
            
            // Set success response with notification data
            $response['success'] = true;
            $response['message'] = 'Reservation submitted successfully!';
            $response['notification'] = [
                'title' => 'Reservation Submitted',
                'message' => "Your reservation request for Room {$lab_room}, PC {$pc_number} has been submitted successfully.",
                'type' => 'info',
                'id' => $reservation_id
            ];
        } else {
            throw new Exception("Failed to save reservation");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $response['message'] = 'An error occurred: ' . $e->getMessage();
    }
    
    // Return JSON response
    echo json_encode($response);
    exit;
}


// AJAX handler for getting available PCs
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_available_pcs'])) {
    $lab_room = $_GET['lab_room'] ?? '';
    $date = $_GET['date'] ?? '';
    $time = $_GET['time'] ?? '';
    
    if (empty($lab_room) || empty($date) || empty($time)) {
        echo json_encode(['success' => false, 'message' => 'Required parameters missing']);
        exit;
    }
    
    try {
        // Get time for query
        $time_in = $time . ':00';
        
        // Get all existing computers for this lab from the database
        $sql = "SELECT pc_number, status
                FROM lab_computers 
                WHERE lab_room = ?
                ORDER BY pc_number";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $lab_room);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Collect all PCs in this lab and normalize status
        $all_pcs = [];
        while ($row = $result->fetch_assoc()) {
            $status = $row['status'];
            // Convert unknown to available (match admin side behavior)
            if ($status === 'unknown' || empty($status)) {
                $status = 'available';
            }
            $all_pcs[$row['pc_number']] = [
                'pc_number' => $row['pc_number'],
                'status' => $status
            ];
        }
        
        // Ensure PCs 1-50 exist with default 'available' status
        for ($i = 1; $i <= 50; $i++) {
            if (!isset($all_pcs[$i])) {
                $all_pcs[$i] = [
                    'pc_number' => $i,
                    'status' => 'available'
                ];
            }
        }
        
        // Now check reservations to exclude PCs that are reserved
        $reservation_sql = "SELECT pc_number 
                           FROM reservations 
                           WHERE lab_room = ? 
                           AND reservation_date = ?
                           AND status IN ('pending', 'approved')
                           AND (
                               /* Check if this reservation starts at the exact same time */
                               time_in = ? 
                               OR 
                               /* Check if there's an approved session with no end time */
                               (status = 'approved' AND time_out IS NULL)
                               OR
                               /* Check if there's an approved session that hasn't ended yet */
                               (time_in < ? AND (time_out IS NULL OR time_out > ?))
                           )";
        
        $stmt = $conn->prepare($reservation_sql);
        $stmt->bind_param("sssss", $lab_room, $date, $time_in, $time_in, $time_in);
        $stmt->execute();
        $reservation_result = $stmt->get_result();
        
        // Mark reserved PCs
        while ($row = $reservation_result->fetch_assoc()) {
            if (isset($all_pcs[$row['pc_number']])) {
                // Remove PCs that are reserved or in use
                unset($all_pcs[$row['pc_number']]);
            }
        }
        
        // Filter out non-available PCs
        $available_pcs = [];
        foreach ($all_pcs as $pc) {
            if ($pc['status'] === 'available') {
                $available_pcs[] = $pc;
            }
        }
        
        // Sort by PC number
        usort($available_pcs, function($a, $b) {
            return $a['pc_number'] - $b['pc_number'];
        });
        
        echo json_encode([
            'success' => true, 
            'data' => array_values($available_pcs)
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>