<?php
// Start the session to ensure we have access to session variables
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Include the database connection
include('../conn/dbcon.php');

if (isset($_POST['student_id']) && isset($_POST['convert_points'])) {
    $student_id = intval($_POST['student_id']);
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Check current points
        $pointsQuery = "SELECT SUM(points_earned) as total_points 
                       FROM student_points 
                       WHERE user_id = ? AND converted_to_session = 0";
        $pointsStmt = $conn->prepare($pointsQuery);
        $pointsStmt->bind_param("i", $student_id);
        $pointsStmt->execute();
        $pointsResult = $pointsStmt->get_result();
        $pointsData = $pointsResult->fetch_assoc();
        $totalPoints = $pointsData['total_points'] ?? 0;
        
        // Verify student has enough points
        if ($totalPoints < 3) {
            throw new Exception('Student does not have enough points to convert (minimum 3 required).');
        }
        
        // Mark points as converted
        $updateStmt = $conn->prepare("UPDATE student_points SET converted_to_session = 1 
                                    WHERE user_id = ? AND converted_to_session = 0");
        $updateStmt->bind_param("i", $student_id);
        $updateStmt->execute();
        
        // Check current sessions
        $sessionQuery = "SELECT session FROM stud_session WHERE id = ?";
        $sessionStmt = $conn->prepare($sessionQuery);
        $sessionStmt->bind_param("i", $student_id);
        $sessionStmt->execute();
        $sessionResult = $sessionStmt->get_result();
        $sessionData = $sessionResult->fetch_assoc();
        $currentSessions = $sessionData['session'];
        
        // Only add session if not already at max
        if ($currentSessions < 30) {
            $sessionStmt = $conn->prepare("UPDATE stud_session SET session = session + 1 WHERE id = ?");
            $sessionStmt->bind_param("i", $student_id);
            $sessionStmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        // Return success
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}
?>