<?php
// Start the session to ensure we have access to session variables
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Include the database connection
include('../conn/dbcon.php');

if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
    
    // Check current sessions
    $sessionQuery = "SELECT session FROM stud_session WHERE id = ?";
    $sessionStmt = $conn->prepare($sessionQuery);
    $sessionStmt->bind_param("i", $student_id);
    $sessionStmt->execute();
    $sessionResult = $sessionStmt->get_result();
    
    if ($sessionResult && $sessionRow = $sessionResult->fetch_assoc()) {
        $hasMaxSessions = ($sessionRow['session'] >= 30);
        echo json_encode(['hasMaxSessions' => $hasMaxSessions]);
    } else {
        echo json_encode(['error' => 'Student not found', 'hasMaxSessions' => false]);
    }
} else {
    echo json_encode(['error' => 'Missing student ID', 'hasMaxSessions' => false]);
}
?>