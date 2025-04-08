<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Process point assignment if form is submitted
if (isset($_POST['assign_points'])) {
    $user_id = $_POST['student_id'];
    $points = $_POST['points'];
    $reason = $_POST['reason'];
    $awarded_by = $_SESSION['admin'];
    
    // Validate points (must be between 1 and 3)
    if ($points >= 1 && $points <= 3) {
        // First check if student already has max sessions
        $sessionQuery = "SELECT session FROM stud_session WHERE id = ?";
        $sessionStmt = $conn->prepare($sessionQuery);
        $sessionStmt->bind_param("i", $user_id);
        $sessionStmt->execute();
        $sessionResult = $sessionStmt->get_result();
        $sessionData = $sessionResult->fetch_assoc();
        $currentSessions = $sessionData['session'];
        
        // If we get here, student doesn't have max sessions, proceed with point assignment
        $stmt = $conn->prepare("INSERT INTO student_points (user_id, points_earned, points_reason, awarded_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $points, $reason, $awarded_by);
        
        if ($stmt->execute()) {
            // Check if student has accumulated 3 or more unconverted points
            $totalPointsQuery = "SELECT SUM(points_earned) as total_points 
                               FROM student_points 
                               WHERE user_id = ? AND converted_to_session = 0";
            $totalStmt = $conn->prepare($totalPointsQuery);
            $totalStmt->bind_param("i", $user_id);
            $totalStmt->execute();
            $result = $totalStmt->get_result();
            $pointsData = $result->fetch_assoc();
            $totalPoints = $pointsData['total_points'];
            
            // If 3 or more points, convert to a session
            if ($totalPoints >= 3) {
                // We've already confirmed they don't have max sessions
                
                // Mark points as converted
                $updateStmt = $conn->prepare("UPDATE student_points SET converted_to_session = 1 
                                            WHERE user_id = ? AND converted_to_session = 0");
                $updateStmt->bind_param("i", $user_id);
                $updateStmt->execute();
                
                // Add a session to the student's account
                $sessionStmt = $conn->prepare("UPDATE stud_session SET session = session + 1 WHERE id = ?");
                $sessionStmt->bind_param("i", $user_id);
                $sessionStmt->execute();
                
                $_SESSION['success_message'] = "Points assigned successfully and converted to a bonus session!";
            } else {
                $_SESSION['success_message'] = "Points assigned successfully! Student needs " . (3 - $totalPoints) . " more points for a bonus session.";
            }
        } else {
            $_SESSION['error_message'] = "Error assigning points. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Points must be between 1 and 3.";
    }
    
    // Redirect to prevent form resubmission
    header("Location: admin_points.php");
    exit();
}

// Process point conversion if form is submitted
if (isset($_POST['convert_points'])) {
    $user_id = $_POST['student_id'];
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Check current points
        $pointsQuery = "SELECT SUM(points_earned) as total_points 
                       FROM student_points 
                       WHERE user_id = ? AND converted_to_session = 0";
        $pointsStmt = $conn->prepare($pointsQuery);
        $pointsStmt->bind_param("i", $user_id);
        $pointsStmt->execute();
        $pointsResult = $pointsStmt->get_result();
        $pointsData = $pointsResult->fetch_assoc();
        $totalPoints = $pointsData['total_points'] ?? 0;
        
        // Verify student has enough points
        if ($totalPoints < 3) {
            throw new Exception('Student does not have enough points to convert (minimum 3 required).');
        }
        
        // Check current sessions
        $sessionQuery = "SELECT session FROM stud_session WHERE id = ?";
        $sessionStmt = $conn->prepare($sessionQuery);
        $sessionStmt->bind_param("i", $user_id);
        $sessionStmt->execute();
        $sessionResult = $sessionStmt->get_result();
        $sessionData = $sessionResult->fetch_assoc();
        $currentSessions = $sessionData['session'];
        
        // Check if student has maximum sessions
        if ($currentSessions >= 30) {
            throw new Exception('Cannot convert points! This student already has the maximum 30 sessions. Points can only be converted after they use some of their current sessions.');
        }
        
        // Mark points as converted
        $updateStmt = $conn->prepare("UPDATE student_points SET converted_to_session = 1 
                                    WHERE user_id = ? AND converted_to_session = 0");
        $updateStmt->bind_param("i", $user_id);
        $updateStmt->execute();
        
        // Add a session to the student's account
        $sessionStmt = $conn->prepare("UPDATE stud_session SET session = session + 1 WHERE id = ?");
        $sessionStmt->bind_param("i", $user_id);
        $sessionStmt->execute();
        $_SESSION['success_message'] = "Points successfully converted to a bonus session!";
        
        // Commit transaction
        $conn->commit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error_message'] = $e->getMessage();
    }
    
    // Redirect to prevent form resubmission
    header("Location: admin_points.php");
    exit();
}

// Fetch all students with their point information
$query = "SELECT u.id, u.idno, CONCAT(u.firstname, ' ', u.lastname) as name, u.email, u.level, u.course, u.profileImg, 
         (SELECT SUM(points_earned) FROM student_points WHERE user_id = u.id AND converted_to_session = 0) as current_points,
         (SELECT COUNT(*) FROM student_points WHERE user_id = u.id) as total_points_earned,
         (SELECT COUNT(*) FROM student_points WHERE user_id = u.id AND converted_to_session = 1) / 3 as sessions_earned,
         (SELECT session FROM stud_session WHERE id = u.id) as current_sessions
         FROM users u
         ORDER BY current_points DESC, name ASC";

$result = $conn->query($query);
$students = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Set current_points to 0 if NULL (no points yet)
        if ($row['current_points'] === NULL) {
            $row['current_points'] = 0;
        }
        
        // Set sessions_earned to 0 if NULL (no sessions yet)
        if ($row['sessions_earned'] === NULL) {
            $row['sessions_earned'] = 0;
        } else {
            $row['sessions_earned'] = floor($row['sessions_earned']);
        }
        
        // Set total_points_earned to 0 if NULL (no points ever)
        if ($row['total_points_earned'] === NULL) {
            $row['total_points_earned'] = 0;
        }
        
        // Ensure current_sessions is set
        if ($row['current_sessions'] === NULL) {
            $row['current_sessions'] = 0;
        }
        
        $students[] = $row;
    }
}

// Get point assignment statistics - check if table exists first
$statsQuery = "SELECT 
    COUNT(*) as total_point_assignments,
    SUM(points_earned) as total_points_awarded,
    COUNT(DISTINCT user_id) as students_with_points,
    SUM(CASE WHEN converted_to_session = 1 THEN points_earned ELSE 0 END) / 3 as sessions_awarded
    FROM student_points";

$stats = [
    'total_point_assignments' => 0,
    'total_points_awarded' => 0,
    'students_with_points' => 0,
    'sessions_awarded' => 0
];

$tableExists = $conn->query("SHOW TABLES LIKE 'student_points'");
if ($tableExists->num_rows > 0) {
    $statsResult = $conn->query($statsQuery);
    if ($statsResult && $statsResult->num_rows > 0) {
        $stats = $statsResult->fetch_assoc();
    }
}

// Format stats for display
$totalAssignments = $stats['total_point_assignments'] ?: 0;
$totalPointsAwarded = $stats['total_points_awarded'] ?: 0;
$studentsWithPoints = $stats['students_with_points'] ?: 0;
$sessionsAwarded = floor($stats['sessions_awarded'] ?: 0);

// Get recent point assignments - check if table exists first
$recentPoints = [];

$tableExists = $conn->query("SHOW TABLES LIKE 'student_points'");
if ($tableExists->num_rows > 0) {
    $recentQuery = "SELECT sp.point_id, sp.user_id, CONCAT(u.firstname, ' ', u.lastname) as student_name, 
                   sp.points_earned, sp.points_reason, sp.awarded_by, sp.awarded_date, sp.converted_to_session
                   FROM student_points sp
                   JOIN users u ON sp.user_id = u.id
                   ORDER BY sp.awarded_date DESC
                   LIMIT 10";
    $recentResult = $conn->query($recentQuery);

    if ($recentResult && $recentResult->num_rows > 0) {
        while ($row = $recentResult->fetch_assoc()) {
            $recentPoints[] = $row;
        }
    }
}
?>