<?php 
// Default date range (last 30 days)
$endDate = date('Y-m-d');
$startDate = date('Y-m-d', strtotime('-30 days'));

// Filter variables
$filterLab = isset($_GET['laboratory']) ? $_GET['laboratory'] : '';
$filterDate = isset($_GET['date']) ? $_GET['date'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// According to your SQL dump, the correct table for sessions is 'curr_sit_in', not 'stud_session'
// 'stud_session' just stores session durations, not actual visit data

// Get all dates for dropdown
$datesQuery = "SELECT DISTINCT DATE(check_in_time) as session_date FROM curr_sit_in ORDER BY session_date DESC";
$datesResult = $conn->query($datesQuery);
$dates = [];
while($dateRow = $datesResult->fetch_assoc()) {
    $dates[] = $dateRow['session_date'];
}

// Get all laboratories for dropdown
$labsQuery = "SELECT DISTINCT laboratory FROM curr_sit_in ORDER BY laboratory";
$labsResult = $conn->query($labsQuery);
$laboratories = [];
while($labRow = $labsResult->fetch_assoc()) {
    $laboratories[] = $labRow['laboratory'];
}

// Dashboard statistics
$statsQuery = "SELECT 
    COUNT(*) as total_sessions,
    COUNT(DISTINCT user_id) as total_students,
    SUM(TIMESTAMPDIFF(MINUTE, check_in_time, IFNULL(check_out_time, NOW()))) as total_minutes
    FROM curr_sit_in 
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";

$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Calculate hours and minutes for display
$totalMinutes = $stats['total_minutes'] ?? 0;
$totalHours = floor($totalMinutes / 60);
$totalMinutes = $totalMinutes % 60;

// Calculate average session duration
$avgMinutes = 0;
if($stats['total_sessions'] > 0) {
    $avgMinutes = floor(($stats['total_minutes'] ?? 0) / $stats['total_sessions']);
}
$avgHours = floor($avgMinutes / 60);
$avgMinutesRemaining = $avgMinutes % 60;

// Get daily visit trend data
$trendsQuery = "SELECT 
    DATE(check_in_time) as visit_date, 
    COUNT(*) as visit_count 
    FROM curr_sit_in 
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' 
    GROUP BY visit_date 
    ORDER BY visit_date";
$trendsResult = $conn->query($trendsQuery);

$dates = [];
$visits = [];
while($trend = $trendsResult->fetch_assoc()) {
    $dates[] = date('M d', strtotime($trend['visit_date']));
    $visits[] = intval($trend['visit_count']);
}

// Get lab usage distribution
$labsUsageQuery = "SELECT 
    laboratory, 
    COUNT(*) as session_count 
    FROM curr_sit_in 
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' 
    GROUP BY laboratory 
    ORDER BY session_count DESC";
$labsUsageResult = $conn->query($labsUsageQuery);

$labLabels = [];
$labCounts = [];
while($lab = $labsUsageResult->fetch_assoc()) {
    $labLabels[] = 'Room ' . $lab['laboratory'];
    $labCounts[] = intval($lab['session_count']);
}

// Get purpose distribution
$purposesQuery = "SELECT 
    purpose, 
    COUNT(*) as purpose_count 
    FROM curr_sit_in 
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' 
    GROUP BY purpose 
    ORDER BY purpose_count DESC
    LIMIT 5";
$purposesResult = $conn->query($purposesQuery);

$purposeLabels = [];
$purposeCounts = [];
while($purpose = $purposesResult->fetch_assoc()) {
    $purposeLabels[] = $purpose['purpose'];
    $purposeCounts[] = intval($purpose['purpose_count']);
}

// Peak hours analysis
$peakHoursQuery = "SELECT 
    HOUR(check_in_time) as hour_of_day,
    COUNT(*) as check_in_count
    FROM curr_sit_in
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
    GROUP BY hour_of_day
    ORDER BY hour_of_day";
$peakHoursResult = $conn->query($peakHoursQuery);

$hourLabels = [];
$hourCounts = [];
while($hour = $peakHoursResult->fetch_assoc()) {
    // Convert 24-hour format to 12-hour with AM/PM
    $formattedHour = date("ga", strtotime($hour['hour_of_day'] . ":00"));
    $hourLabels[] = $formattedHour;
    $hourCounts[] = intval($hour['check_in_count']);
}

// Main query for table display
$query = "SELECT s.*, u.firstname, u.lastname, u.midname, u.course, u.level, u.idno FROM curr_sit_in s 
          LEFT JOIN users u ON s.user_id = u.id WHERE 1=1";

if (!empty($filterLab)) {
    $query .= " AND s.laboratory = '$filterLab'";
}

if (!empty($filterDate)) {
    $query .= " AND DATE(s.check_in_time) = '$filterDate'";
}

if (!empty($filterStatus)) {
    $query .= " AND s.status = '$filterStatus'";
}

if (!empty($searchTerm)) {
    $query .= " AND (u.idno LIKE '%$searchTerm%' OR u.firstname LIKE '%$searchTerm%' OR u.lastname LIKE '%$searchTerm%')";
}

$query .= " ORDER BY s.check_in_time DESC";
$result = $conn->query($query);

// Find busiest day of the week
$busiestDayQuery = "SELECT 
    DAYNAME(check_in_time) as day_name, 
    COUNT(*) as session_count
    FROM curr_sit_in
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
    GROUP BY day_name
    ORDER BY session_count DESC
    LIMIT 1";
$busiestDayResult = $conn->query($busiestDayQuery);
$busiestDay = $busiestDayResult->fetch_assoc();

// Find average sessions per day
$avgSessionsQuery = "SELECT 
    COUNT(*) / COUNT(DISTINCT DATE(check_in_time)) as avg_sessions_per_day
    FROM curr_sit_in
    WHERE check_in_time BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
$avgSessionsResult = $conn->query($avgSessionsQuery);
$avgSessions = $avgSessionsResult->fetch_assoc();
$avgSessionsPerDay = round($avgSessions['avg_sessions_per_day'], 1);
?>