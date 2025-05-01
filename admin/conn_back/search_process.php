<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Initialize variables
$searchResults = [];
$searchPerformed = false;
$laboratories = ["524", "526", "528", "530", "542", "544", "517"];

// Initialize recent searches array in session if it doesn't exist
if (!isset($_SESSION['recent_searches'])) {
    $_SESSION['recent_searches'] = [];
}

// Handle search
if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
    $searchPerformed = true;
    $searchTerm = trim($_GET['search_term']);
    $searchTermForQuery = "%" . $searchTerm . "%";
    
    // Add the current search to recent searches
    $searchType = is_numeric(str_replace('-', '', $searchTerm)) ? 'id' : 'name';
    
    // Create new search entry
    $newSearch = ['type' => $searchType, 'value' => $searchTerm];
    
    // Remove duplicate if exists
    $_SESSION['recent_searches'] = array_filter($_SESSION['recent_searches'], function($item) use ($searchTerm) {
        return $item['value'] !== $searchTerm;
    });
    
    // Add new search to the beginning of the array
    array_unshift($_SESSION['recent_searches'], $newSearch);
    
    // Keep only the 5 most recent searches
    $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
    
    // Search for students by ID or name (rest of your search code)
    $query = "SELECT u.id, u.idno, u.firstname, u.midname, u.lastname, u.course, u.level, 
                    u.email, u.profileImg, ss.session as remaining_sessions,
                    (SELECT MAX(check_in_time) FROM curr_sit_in WHERE user_id = u.id) as last_sit_in
              FROM users u 
              LEFT JOIN stud_session ss ON u.id = ss.id 
              WHERE u.idno LIKE ? OR 
                    u.firstname LIKE ? OR 
                    u.lastname LIKE ? OR 
                    CONCAT(u.firstname, ' ', u.lastname) LIKE ? OR
                    CONCAT(u.lastname, ', ', u.firstname) LIKE ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $searchTermForQuery, $searchTermForQuery, $searchTermForQuery, $searchTermForQuery, $searchTermForQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

// Handle the sit-in registration form submission
if (isset($_POST['register_sit_in'])) {
    $user_id = $_POST['user_id'];
    $laboratory = $_POST['laboratory'];
    $purpose = $_POST['purpose'];
    
    // First check if the user already has an active sit-in session
    $checkQuery = "SELECT * FROM curr_sit_in WHERE user_id = ? AND status = 'active'";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $_SESSION['message'] = "This student already has an active sit-in session!";
        $_SESSION['msg_type'] = "warning";
    } else {
        // Check if the student has any remaining sessions
        $sessionQuery = "SELECT session FROM stud_session WHERE id = ? AND session > 0";
        $sessionStmt = $conn->prepare($sessionQuery);
        $sessionStmt->bind_param("i", $user_id);
        $sessionStmt->execute();
        $sessionResult = $sessionStmt->get_result();
        
        if ($sessionResult->num_rows > 0) {
            // Insert new sit-in record without decrementing session count
            $insertQuery = "INSERT INTO curr_sit_in (user_id, laboratory, purpose) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("iss", $user_id, $laboratory, $purpose);
            
            if ($insertStmt->execute()) {
                $_SESSION['message'] = "Sit-in session successfully scheduled.";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Error scheduling sit-in session.";
                $_SESSION['msg_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "Student has no remaining sessions";
            $_SESSION['msg_type'] = "error";
        }
    }
    
    // Redirect to the same page to prevent form resubmission
    header("Location: admin_search.php?search_term=" . urlencode($_POST['search_term']));
    exit();
}


// Get recent searches from session
$recentSearches = $_SESSION['recent_searches'] ?? [];
?>