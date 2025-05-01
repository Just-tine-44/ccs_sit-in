<?php 
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Handle student deletion
if(isset($_POST['delete_student'])) {
    $student_id = $_POST['student_id'];
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $student_id);
    
    if($stmt->execute()) {
        $_SESSION['message'] = "Student successfully deleted";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: Could not delete student";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: admin_student_list.php");
    exit();
}

// Handle session reset for a specific student
if(isset($_POST['reset_sessions'])) {
    $student_id = $_POST['student_id'];
    $updateQuery = "UPDATE stud_session SET session = 30 WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $student_id);
    
    if($stmt->execute()) {
        $_SESSION['message'] = "Student sessions reset successfully";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: Could not reset sessions";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: admin_student_list.php");
    exit();
}

// Reset all students' sessions
if(isset($_POST['reset_all_sessions'])) {
    $updateAllQuery = "UPDATE stud_session SET session = 30";
    if($conn->query($updateAllQuery)) {
        $_SESSION['message'] = "All sessions reset successfully";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: Could not reset all sessions";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: admin_student_list.php");
    exit();
}

// Pagination settings
$results_per_page = 10;
if(isset($_GET['entries'])) {
    $results_per_page = intval($_GET['entries']);
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($page - 1) * $results_per_page;

// Search functionality
$search = '';
$searchCondition = '';
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchCondition = "WHERE idno LIKE ? OR CONCAT(firstname, ' ', lastname) LIKE ? OR course LIKE ?";
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM users " . $searchCondition;
$countStmt = $conn->prepare($countQuery);

if(!empty($searchCondition)) {
    $searchParam = "%{$search}%";
    $countStmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
}

$countStmt->execute();
$totalResult = $countStmt->get_result()->fetch_assoc();
$total_records = $totalResult['total'];
$total_pages = ceil($total_records / $results_per_page);

// Get users with pagination and search
$query = "SELECT u.*, s.session 
        FROM users u 
        LEFT JOIN stud_session s ON u.id = s.id " . 
        $searchCondition . 
        " ORDER BY u.id DESC LIMIT ?, ?";
$stmt = $conn->prepare($query);
    
if(!empty($searchCondition)) {
    $searchParam = "%{$search}%";
    $stmt->bind_param("sssis", $searchParam, $searchParam, $searchParam, $start_from, $results_per_page);
} else {
    $stmt->bind_param("ii", $start_from, $results_per_page);
}
    
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);

?>