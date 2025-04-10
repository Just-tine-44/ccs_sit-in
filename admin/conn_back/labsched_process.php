<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Handle form submission for adding a new schedule
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['upload_schedule'])) {
        $lab_room = $_POST['lab_room'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $uploaded_by = $_SESSION['admin_username'];
        
        // File upload handling
        $target_dir = "../uploads/schedules/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES["schedule_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = 'schedule_' . $lab_room . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["schedule_image"]["tmp_name"]);
        if ($check === false) {
            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">File is not an image.</div>';
        } else {
            // Check file size (limit to 5MB)
            if ($_FILES["schedule_image"]["size"] > 5000000) {
                $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">File is too large. Max size: 5MB</div>';
            } else {
                // Allow only certain file formats
                if ($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
                    $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Only JPG, JPEG & PNG files are allowed.</div>';
                } else {
                    // Upload file
                    if (move_uploaded_file($_FILES["schedule_image"]["tmp_name"], $target_file)) {
                        // Insert into database
                        $sql = "INSERT INTO lab_schedules (lab_room, schedule_image, title, description, uploaded_by) 
                                VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssss", $lab_room, $new_filename, $title, $description, $uploaded_by);
                        
                        if ($stmt->execute()) {
                            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">Schedule uploaded successfully!</div>';
                        } else {
                            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Error: ' . $stmt->error . '</div>';
                        }
                        $stmt->close();
                    } else {
                        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Error uploading file.</div>';
                    }
                }
            }
        }
    }
    
    // Handle schedule deletion
    if (isset($_POST['delete_schedule'])) {
        $schedule_id = $_POST['schedule_id'];
        
        // Get the filename to delete the file
        $sql = "SELECT schedule_image FROM lab_schedules WHERE schedule_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $filename = "../uploads/schedules/" . $row['schedule_image'];
            
            // Delete from database
            $delete_sql = "DELETE FROM lab_schedules WHERE schedule_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $schedule_id);
            
            if ($delete_stmt->execute()) {
                // Delete the file if exists
                if (file_exists($filename)) {
                    unlink($filename);
                }
                $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">Schedule deleted successfully!</div>';
            } else {
                $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Error deleting schedule.</div>';
            }
            $delete_stmt->close();
        }
        $stmt->close();
    }
}

// Fetch all schedules for display
$sql = "SELECT * FROM lab_schedules ORDER BY lab_room, upload_date DESC";
$result = $conn->query($sql);
?>