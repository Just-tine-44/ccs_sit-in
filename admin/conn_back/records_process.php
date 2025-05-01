<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Initialize variables for filtering
$filterLab = isset($_GET['laboratory']) ? $_GET['laboratory'] : '';
$filterDate = isset($_GET['date']) ? $_GET['date'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'sessions';

// Prepare base query for sit-in sessions
$query = "SELECT s.sit_in_id, s.user_id, s.laboratory, s.purpose, s.check_in_time, s.check_out_time, s.status,
                 u.idno, u.firstname, u.midname, u.lastname, u.course, u.level
          FROM curr_sit_in s
          JOIN users u ON s.user_id = u.id
          WHERE 1=1";

// Add filters to query
$params = [];
$types = "";

if (!empty($filterLab)) {
    $query .= " AND s.laboratory = ?";
    $params[] = $filterLab;
    $types .= "s";
}

if (!empty($filterDate)) {
    $query .= " AND DATE(s.check_in_time) = ?";
    $params[] = $filterDate;
    $types .= "s";
}

if (!empty($filterStatus)) {
    $query .= " AND s.status = ?";
    $params[] = $filterStatus;
    $types .= "s";
}

if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $query .= " AND (u.idno LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ? OR CONCAT(u.firstname, ' ', u.lastname) LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ssss";
}

// Add ordering
$query .= " ORDER BY s.check_in_time DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Query for reservations - FIXED: Using lab_room instead of laboratory
$reservationQuery = "SELECT r.reservation_id, r.user_id, r.lab_room as laboratory, r.purpose, r.reservation_date, 
                          r.time_in as start_time, r.time_out as end_time, r.status,
                          u.idno, u.firstname, u.midname, u.lastname, u.course, u.level
                    FROM reservations r
                    JOIN users u ON r.user_id = u.id
                    WHERE 1=1";

// Add filters to reservation query
$reservationParams = [];
$reservationTypes = "";

if (!empty($filterLab)) {
    $reservationQuery .= " AND r.lab_room = ?"; // FIXED: lab_room instead of laboratory
    $reservationParams[] = $filterLab;
    $reservationTypes .= "s";
}

if (!empty($filterDate)) {
    $reservationQuery .= " AND DATE(r.reservation_date) = ?";
    $reservationParams[] = $filterDate;
    $reservationTypes .= "s";
}

if (!empty($filterStatus)) {
    $reservationQuery .= " AND r.status = ?";
    $reservationParams[] = $filterStatus;
    $reservationTypes .= "s";
}

if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $reservationQuery .= " AND (u.idno LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ? OR CONCAT(u.firstname, ' ', u.lastname) LIKE ?)";
    $reservationParams[] = $searchTerm;
    $reservationParams[] = $searchTerm;
    $reservationParams[] = $searchTerm;
    $reservationParams[] = $searchTerm;
    $reservationTypes .= "ssss";
}

// Add ordering for reservations
$reservationQuery .= " ORDER BY r.reservation_date DESC, r.time_in DESC"; // FIXED: time_in instead of start_time

// Prepare and execute the reservation query
$reservationStmt = $conn->prepare($reservationQuery);
if (!empty($reservationParams)) {
    $reservationStmt->bind_param($reservationTypes, ...$reservationParams);
}
$reservationStmt->execute();
$reservationResult = $reservationStmt->get_result();

// Get statistics for dashboard
$statsQuery = "SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_sessions,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_sessions,
                COUNT(DISTINCT user_id) as unique_students,
                SUM(CASE WHEN check_out_time IS NOT NULL 
                    THEN GREATEST(0, TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time))
                    ELSE 0 END) as total_minutes
               FROM curr_sit_in";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Get list of laboratories for filter - combine from both tables
$labQuery = "SELECT lab_value FROM (
                SELECT DISTINCT laboratory as lab_value FROM curr_sit_in
                UNION
                SELECT DISTINCT lab_room as lab_value FROM reservations
            ) AS labs
            ORDER BY lab_value";
$labResult = $conn->query($labQuery);
$laboratories = [];
while ($row = $labResult->fetch_assoc()) {
    $laboratories[] = $row['lab_value'];
}

// Get dates for filter - combine from both tables
$dateQuery = "SELECT date_val FROM (
                SELECT DISTINCT DATE(check_in_time) as date_val FROM curr_sit_in
                UNION
                SELECT DISTINCT reservation_date as date_val FROM reservations
            ) AS dates
            ORDER BY date_val DESC";
$dateResult = $conn->query($dateQuery);
$dates = [];
while ($row = $dateResult->fetch_assoc()) {
    $dates[] = $row['date_val'];
}
?>