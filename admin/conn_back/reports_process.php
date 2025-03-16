<?php 
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Database connection
include("../conn/dbcon.php");

// Get date range filters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Get overall statistics
$statsQuery = "SELECT 
                COUNT(*) as total_sessions,
                COUNT(DISTINCT user_id) as total_students,
                SUM(TIMESTAMPDIFF(MINUTE, check_in_time, 
                    CASE WHEN check_out_time IS NULL THEN NOW() ELSE check_out_time END)) / 60 as total_hours
              FROM 
                curr_sit_in
              WHERE 
                check_in_time BETWEEN '$startDate' AND '$endDate 23:59:59'";

$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Format hours
$totalHours = floor($stats['total_hours']);
$totalMinutes = round(($stats['total_hours'] - $totalHours) * 60);

// Average session duration
$avgDurationQuery = "SELECT 
                        AVG(TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time)) as avg_minutes
                     FROM 
                        curr_sit_in
                     WHERE 
                        check_out_time IS NOT NULL
                        AND check_in_time BETWEEN '$startDate' AND '$endDate 23:59:59'";
                        
$avgDurationResult = $conn->query($avgDurationQuery);
$avgDuration = $avgDurationResult->fetch_assoc();
$avgMinutes = round($avgDuration['avg_minutes']);
$avgHours = floor($avgMinutes / 60);
$avgMinutesRemaining = $avgMinutes % 60;

// Laboratory usage distribution
$labQuery = "SELECT laboratory, COUNT(*) as count
            FROM curr_sit_in
            WHERE check_in_time BETWEEN '$startDate' AND '$endDate 23:59:59'
            GROUP BY laboratory
            ORDER BY count DESC";
            
$labResult = $conn->query($labQuery);
$labLabels = [];
$labCounts = [];

while ($row = $labResult->fetch_assoc()) {
    $labLabels[] = $row['laboratory'];
    $labCounts[] = $row['count'];
}

// Purpose distribution
$purposeQuery = "SELECT purpose, COUNT(*) as count
                FROM curr_sit_in
                WHERE check_in_time BETWEEN '$startDate' AND '$endDate 23:59:59'
                GROUP BY purpose
                ORDER BY count DESC
                LIMIT 5";
                
$purposeResult = $conn->query($purposeQuery);
$purposeLabels = [];
$purposeCounts = [];

while ($row = $purposeResult->fetch_assoc()) {
    $purposeLabels[] = $row['purpose'];
    $purposeCounts[] = $row['count'];
}

// Daily visits trend
$dailyQuery = "SELECT DATE(check_in_time) as date, COUNT(*) as visits
               FROM curr_sit_in
               WHERE check_in_time BETWEEN '$startDate' AND '$endDate 23:59:59'
               GROUP BY DATE(check_in_time)
               ORDER BY date";
                  
$dailyResult = $conn->query($dailyQuery);
$dates = [];
$visits = [];

while ($row = $dailyResult->fetch_assoc()) {
    $dates[] = date('M d', strtotime($row['date']));
    $visits[] = $row['visits'];
}
?>