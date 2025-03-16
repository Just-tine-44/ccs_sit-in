<?php
// filepath: c:\xampp\htdocs\login\verify_admin_code.php
// Set appropriate headers first
header('Content-Type: application/json');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Prevent unexpected output
ob_clean();

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$code = isset($data['code']) ? trim($data['code']) : '';

// Basic validation
if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Authentication code required']);
    exit;
}

try {
    // Connect to database
    require 'conn/dbcon.php';
    
    // Get all admin passwords from database
    $stmt = $conn->prepare("SELECT password FROM admin");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $access_granted = false;
    
    // Check if the code matches any admin password
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stored_password = $row['password'];
            
            // Check if the code matches this admin password
            if ($code === $stored_password) {
                $access_granted = true;
                break; // Exit the loop once we find a match
            }
        }
    }
    
    // Set session variable and return response
    if ($access_granted) {
        $_SESSION['auth_verified'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid authentication code']);
    }
    
    // Close connection
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    // Log error but don't expose details to client
    error_log('Error in verify_admin_code.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}

exit;
?>