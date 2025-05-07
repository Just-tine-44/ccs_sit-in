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
            $upload_dir = "../../uploads/resources/";
            
            $file_name = time() . '_' . basename($_FILES['resource_file']['name']);
            
            // Add file type validation for security
            $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'mp4', 'avi', 'mov'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if (!in_array($file_extension, $allowed_types)) {
                $_SESSION['error_message'] = "Error: Only document and video files are allowed.";
                header("Location: ../admin_resources.php");
                exit();
            }
            
            $target_file = $upload_dir . $file_name;
            
            // Check if directory exists and create if needed
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Move uploaded file to destination
            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $target_file)) {
                // Store the path relative to the login directory root
                $file_path = 'uploads/resources/' . $file_name;
            } else {
                $error = error_get_last();
                $_SESSION['error_message'] = "Error uploading file. Please try again. " . 
                    ($error ? " Details: " . $error['message'] : "");
                header("Location: ../admin_resources.php");
                exit();
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
        if (!empty($row['file_path'])) {
            $file_to_delete = "../../" . $row['file_path'];
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
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