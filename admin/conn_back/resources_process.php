<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Handle form submission for adding new resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_resource'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $year_level = $_POST['year_level'];
    $course = $_POST['course']; // Added course parameter
    $resource_type = $_POST['resource_type'];
    $uploaded_by = $_SESSION['admin_name'] ?? 'Admin';
    
    $file_path = null;
    $link_url = null;
    
    // Handle file upload
    if ($resource_type === 'document' || $resource_type === 'video') {
        if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
            $upload_dir = "../uploads/resources/";
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['resource_file']['name']);
            $target_file = $upload_dir . $file_name;
            
            // Move uploaded file to destination
            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $target_file)) {
                $file_path = 'uploads/resources/' . $file_name;
            }
        }
    } elseif ($resource_type === 'link' && isset($_POST['link_url'])) {
        $link_url = $_POST['link_url'];
    }
    
    // Insert into database (with course field)
    $query = "INSERT INTO lab_resources (title, description, file_path, link_url, year_level, course, resource_type, uploaded_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $title, $description, $file_path, $link_url, $year_level, $course, $resource_type, $uploaded_by);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Resource added successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: ../admin_resources.php");
    exit();
}

// Handle resource deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $resource_id = $_GET['delete'];
    
    // First get the file path if any
    $query = "SELECT file_path FROM lab_resources WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $resource_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Delete the file if it exists
        if (!empty($row['file_path']) && file_exists('../' . $row['file_path'])) {
            unlink('../' . $row['file_path']);
        }
    }
    
    // Delete from database
    $query = "DELETE FROM lab_resources WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $resource_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Resource deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting resource.";
    }
    
    $stmt->close();
    header("Location: ../admin_resources.php");
    exit();
}

// Fetch all resources for display
$resources = [];
$query = "SELECT * FROM lab_resources ORDER BY upload_date DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
}
?>