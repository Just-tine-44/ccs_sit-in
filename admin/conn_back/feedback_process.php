<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../conn/dbcon.php';


// Filter parameters
$labFilter = isset($_GET['laboratory']) ? $_GET['laboratory'] : '';
$ratingFilter = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
$dateFilter = isset($_GET['date_range']) ? $_GET['date_range'] : '';
$searchFilter = isset($_GET['search']) ? $_GET['search'] : '';

// Build query with filters
$query = "SELECT 
            r.rating_id, r.rating, r.feedback, r.created_at,
            s.sit_in_id, s.laboratory, s.purpose, s.check_in_time, s.check_out_time,
            u.id as user_id, u.idno, u.firstname, u.lastname, u.profileImg
          FROM sit_in_ratings r
          JOIN curr_sit_in s ON r.sit_in_id = s.sit_in_id
          JOIN users u ON s.user_id = u.id
          WHERE 1=1";

$params = [];
$types = '';

// Apply filters
if (!empty($labFilter)) {
    $query .= " AND s.laboratory = ?";
    $params[] = $labFilter;
    $types .= 's';
}

if ($ratingFilter > 0) {
    $query .= " AND r.rating = ?";
    $params[] = $ratingFilter;
    $types .= 'i';
}

if (!empty($dateFilter)) {
    // Parse date range
    $dates = explode(' - ', $dateFilter);
    if (count($dates) == 2) {
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]) + 86400); // Add one day to include all of end date
        
        $query .= " AND s.check_in_time BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= 'ss';
    }
}

if (!empty($searchFilter)) {
    $searchTerm = "%$searchFilter%";
    $query .= " AND (u.idno LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ? OR r.feedback LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ssss';
}

// Add sorting
$query .= " ORDER BY r.created_at DESC";

// Prepare and execute statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get list of laboratories for filter
$labQuery = "SELECT DISTINCT laboratory FROM curr_sit_in ORDER BY laboratory";
$labResult = $conn->query($labQuery);
$laboratories = [];
while ($row = $labResult->fetch_assoc()) {
    $laboratories[] = $row['laboratory'];
}

// Get rating statistics
$statsQuery = "SELECT 
                AVG(r.rating) as avg_rating,
                COUNT(*) as total_ratings,
                COUNT(CASE WHEN r.rating = 5 THEN 1 END) as five_star,
                COUNT(CASE WHEN r.rating = 4 THEN 1 END) as four_star,
                COUNT(CASE WHEN r.rating = 3 THEN 1 END) as three_star,
                COUNT(CASE WHEN r.rating = 2 THEN 1 END) as two_star,
                COUNT(CASE WHEN r.rating = 1 THEN 1 END) as one_star
              FROM sit_in_ratings r";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Calculate percentages for star ratings
$totalRatings = $stats['total_ratings'] > 0 ? $stats['total_ratings'] : 1; // Avoid division by zero
$starPercentages = [
    'five' => ($stats['five_star'] / $totalRatings) * 100,
    'four' => ($stats['four_star'] / $totalRatings) * 100,
    'three' => ($stats['three_star'] / $totalRatings) * 100,
    'two' => ($stats['two_star'] / $totalRatings) * 100,
    'one' => ($stats['one_star'] / $totalRatings) * 100
];
?>