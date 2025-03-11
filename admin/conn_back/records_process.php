<?php
include(__DIR__ . '/../../conn/dbcon.php');

// Initialize variables for filtering
$filterLab = isset($_GET['laboratory']) ? $_GET['laboratory'] : '';
$filterDate = isset($_GET['date']) ? $_GET['date'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare base query
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

// Get statistics for dashboard
$statsQuery = "SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_sessions,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_sessions,
                COUNT(DISTINCT user_id) as unique_students,
                SUM(CASE WHEN check_out_time IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time) 
                    ELSE 0 END) as total_minutes
               FROM curr_sit_in";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Get list of laboratories for filter
$labQuery = "SELECT DISTINCT laboratory FROM curr_sit_in ORDER BY laboratory";
$labResult = $conn->query($labQuery);
$laboratories = [];
while ($row = $labResult->fetch_assoc()) {
    $laboratories[] = $row['laboratory'];
}

// Get dates for filter (unique dates from check_in_time)
$dateQuery = "SELECT DISTINCT DATE(check_in_time) as date FROM curr_sit_in ORDER BY date DESC";
$dateResult = $conn->query($dateQuery);
$dates = [];
while ($row = $dateResult->fetch_assoc()) {
    $dates[] = $row['date'];
}
?>