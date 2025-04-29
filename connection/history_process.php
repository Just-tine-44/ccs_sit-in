<?php
// filepath: /c:/xampp/htdocs/ccs_sit-in/history.php
session_start();
include '../connection/conn_login.php'; // Make sure this is the correct path to your DB connection

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$userId = $user['id'];

// Handle rating submission
if (isset($_POST['submit_rating'])) {
    $sitInId = $_POST['sit_in_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    
    // Check if rating already exists
    $checkQuery = "SELECT rating_id FROM sit_in_ratings WHERE sit_in_id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $sitInId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        // Update existing rating
        $ratingId = $checkResult->fetch_assoc()['rating_id'];
        $updateQuery = "UPDATE sit_in_ratings SET rating = ?, feedback = ? WHERE rating_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("isi", $rating, $feedback, $ratingId);
        
        if ($updateStmt->execute()) {
            $_SESSION['message'] = "Rating updated successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating rating: " . $conn->error;
            $_SESSION['msg_type'] = "error";
        }
    } else {
        // Insert new rating
        $insertQuery = "INSERT INTO sit_in_ratings (sit_in_id, user_id, rating, feedback) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iiis", $sitInId, $userId, $rating, $feedback);
        
        if ($insertStmt->execute()) {
            $_SESSION['message'] = "Thank you for your feedback!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error submitting rating: " . $conn->error;
            $_SESSION['msg_type'] = "error";
        }
    }
    
    // Redirect to avoid form resubmission
    header("Location: history.php");
    exit();
}

// Get user sit-in history
$historyQuery = "SELECT s.sit_in_id, s.purpose, s.laboratory, s.check_in_time, s.check_out_time, 
                      s.status, r.rating, r.feedback
                FROM curr_sit_in s
                LEFT JOIN sit_in_ratings r ON s.sit_in_id = r.sit_in_id AND r.user_id = ?
                WHERE s.user_id = ?
                ORDER BY s.check_in_time DESC";
$historyStmt = $conn->prepare($historyQuery);
$historyStmt->bind_param("ii", $userId, $userId);
$historyStmt->execute();
$historyResult = $historyStmt->get_result();

// Count statistics
$statsQuery = "SELECT 
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_sessions,
                SUM(CASE WHEN r.rating IS NOT NULL THEN 1 ELSE 0 END) as rated_sessions
              FROM curr_sit_in s
              LEFT JOIN sit_in_ratings r ON s.sit_in_id = r.sit_in_id AND r.user_id = ?
              WHERE s.user_id = ?";
$statsStmt = $conn->prepare($statsQuery);
$statsStmt->bind_param("ii", $userId, $userId);
$statsStmt->execute();
$stats = $statsStmt->get_result()->fetch_assoc();

// Calculate usage hours
$hoursQuery = "SELECT SUM(TIMESTAMPDIFF(MINUTE, check_in_time, IFNULL(check_out_time, NOW()))) as total_minutes
               FROM curr_sit_in 
               WHERE user_id = ? AND check_in_time IS NOT NULL";
$hoursStmt = $conn->prepare($hoursQuery);
$hoursStmt->bind_param("i", $userId);
$hoursStmt->execute();
$hoursResult = $hoursStmt->get_result();
$totalMinutes = $hoursResult->fetch_assoc()['total_minutes'] ?? 0;
$totalHours = floor($totalMinutes / 60);
$remainingMinutes = $totalMinutes % 60;

?>
