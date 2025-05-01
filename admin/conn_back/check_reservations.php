<?php
// Start session and include database connection
session_start();
include('../../conn/dbcon.php');

// Check if admin is logged in
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

// Set content type to JSON
header('Content-Type: application/json');

// Check if we're getting already shown notification IDs from the request
$alreadyShownIds = [];
if (isset($_GET['shown_ids']) && !empty($_GET['shown_ids'])) {
    $alreadyShownIds = json_decode($_GET['shown_ids'], true) ?: [];
}

// Get the last check timestamp from admin's session or create one
if (!isset($_SESSION['last_reservation_check'])) {
    $_SESSION['last_reservation_check'] = date('Y-m-d H:i:s', strtotime('-1 minute'));
}

$lastCheck = $_SESSION['last_reservation_check'];

// Method 1: Check Database for new reservations
$query = "SELECT r.reservation_id as id, 
          u.firstname, u.lastname,
          r.lab_room, r.pc_number, r.reservation_date, r.time_in,
          DATE_FORMAT(r.reservation_date, '%b %d, %Y') as date_formatted,
          r.created_at
          FROM reservations r
          JOIN users u ON r.user_id = u.id
          WHERE r.status = 'pending'";

// If we have already shown IDs, exclude them
if (!empty($alreadyShownIds)) {
    $query .= " AND r.reservation_id NOT IN (" . implode(',', array_map('intval', $alreadyShownIds)) . ")";
}

$query .= " ORDER BY r.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$newReservations = [];
while ($row = $result->fetch_assoc()) {
    $newReservations[] = [
        'id' => $row['id'],
        'title' => 'New Reservation Request',
        'message' => $row['firstname'] . ' ' . $row['lastname'] . ' requested PC ' . $row['pc_number'] . ' in Lab ' . $row['lab_room'] . ' for ' . $row['date_formatted'],
        'type' => 'info',
        'isRead' => false,
        'createdAt' => $row['created_at']
    ];
}

// Method 2: Also check the file-based storage as backup
$notificationsFile = './new_reservations.json';
$fileNotifications = [];

if (file_exists($notificationsFile)) {
    $fileContent = file_get_contents($notificationsFile);
    if (!empty($fileContent)) {
        $fileNotifications = json_decode($fileContent, true) ?: [];
        
        // Filter out already shown notifications
        if (!empty($alreadyShownIds)) {
            $fileNotifications = array_filter($fileNotifications, function($notification) use ($alreadyShownIds) {
                return !in_array($notification['id'], $alreadyShownIds);
            });
        }
        
        // Clear the file to avoid showing the same notifications again
        file_put_contents($notificationsFile, json_encode([]));
    }
}

// Combine both sources of notifications
$allNewNotifications = array_merge($newReservations, $fileNotifications);

// Update the last check timestamp to now
$_SESSION['last_reservation_check'] = date('Y-m-d H:i:s');

// Return the results
echo json_encode([
    'success' => true,
    'count' => count($allNewNotifications),
    'notifications' => $allNewNotifications,
    'currentTime' => time() * 1000 // Current time in milliseconds for JavaScript
]);
?>