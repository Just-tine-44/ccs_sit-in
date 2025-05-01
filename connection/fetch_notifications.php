<?php
// Start session and include necessary connections/functions
session_start();
include('../conn/dbcon.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user']['id'];

// Set proper content type for JSON response
header('Content-Type: application/json');

// Handle marking notifications as read
if (isset($_POST['mark_read'])) {
    $notification_id = filter_input(INPUT_POST, 'notification_id', FILTER_VALIDATE_INT);
    
    if ($notification_id) {
        // Mark single notification as read
        $query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $notification_id, $user_id);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
        exit();
    } elseif ($_POST['mark_read'] === 'all') {
        // Mark all notifications as read
        $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
        exit();
    }
}

// Get notifications for this user
$query = "SELECT id, title, message, type, is_read, related_id, created_at 
          FROM notifications 
          WHERE user_id = ? 
          ORDER BY created_at DESC 
          LIMIT 20";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'message' => $row['message'],
        'type' => $row['type'],
        'isRead' => (bool)$row['is_read'],
        'relatedId' => $row['related_id'],
        'createdAt' => $row['created_at']
    ];
}

// Count unread notifications
$query = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$count_result = $stmt->get_result();
$unread_count = $count_result->fetch_assoc()['unread_count'];

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unreadCount' => (int)$unread_count
]);
?>