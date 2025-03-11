<?php
session_start();

// Clear recent searches
$_SESSION['recent_searches'] = [];

// Return success status
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>