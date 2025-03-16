<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Handle student checkout/timeout
if (isset($_POST['checkout'])) {
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
            // 1. Update sit-in record to mark as completed
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
            
            $_SESSION['message'] = "Student checked out and session count updated successfully";
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
    header("Location: sit_in.php");
    exit();
}

// Get all active sit-in sessions with student info
$query = "SELECT s.sit_in_id, s.user_id, s.laboratory, s.purpose, s.check_in_time, 
                 u.idno, u.firstname, u.midname, u.lastname, u.course, u.level, 
                 ss.session as remaining_sessions
          FROM curr_sit_in s
          JOIN users u ON s.user_id = u.id
          JOIN stud_session ss ON u.id = ss.id
          WHERE s.status = 'active'
          ORDER BY s.check_in_time DESC";
$result = $conn->query($query);

// Get active sit-in count for each lab
$labCounts = [];
$countQuery = "SELECT laboratory, COUNT(*) as count FROM curr_sit_in WHERE status = 'active' GROUP BY laboratory";
$countResult = $conn->query($countQuery);

if ($countResult->num_rows > 0) {
    while ($row = $countResult->fetch_assoc()) {
        $labCounts[$row['laboratory']] = $row['count'];
    }
}

// Get total active sit-ins
$totalQuery = "SELECT COUNT(*) as total FROM curr_sit_in WHERE status = 'active'";
$totalResult = $conn->query($totalQuery);
$totalActive = $totalResult->fetch_assoc()['total'];
?>