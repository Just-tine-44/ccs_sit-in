<?php
session_start();

if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: .././user/login.php");
    exit();
}

include(__DIR__ . '/../../conn/dbcon.php');

// Initialize message variables
$message = "";
$sweetAlert = false;
$alertType = "";
$alertTitle = "";
$alertText = "";

// Handle form submission for adding a new schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['upload_schedule'])) {
        $lab_room = $_POST['lab_room'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        // Fix: Get the actual username from the admin table using the admin ID
        $admin_username = "Admin"; // Default fallback
        
        // If we have the admin ID in session, get their username from the database
        if(isset($_SESSION['admin_id'])) {
            $admin_id = $_SESSION['admin_id'];
            $query = "SELECT username FROM admin WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()) {
                $admin_username = $row['username'];
            }
        } else {
            // If we don't have admin_id but have admin username directly
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
                $admin_username = $_SESSION['admin'];
            }
        }
        
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
            $alertType = "error";
            $alertTitle = "Invalid File";
            $alertText = "File is not an image.";
        } else {
            // Check file size (limit to 5MB)
            if ($_FILES["schedule_image"]["size"] > 5000000) {
                $alertType = "error";
                $alertTitle = "File Too Large";
                $alertText = "File is too large. Max size: 5MB";
            } else {
                // Allow only certain file formats
                if ($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
                    $alertType = "error";
                    $alertTitle = "Invalid Format";
                    $alertText = "Only JPG, JPEG & PNG files are allowed.";
                } else {
                    // Upload file
                    if (move_uploaded_file($_FILES["schedule_image"]["tmp_name"], $target_file)) {
                        // Insert into database
                        $sql = "INSERT INTO lab_schedules (lab_room, schedule_image, title, description, uploaded_by) 
                                VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssss", $lab_room, $new_filename, $title, $description, $admin_username);
                        
                        if ($stmt->execute()) {
                            $alertType = "success";
                            $alertTitle = "Success!";
                            $alertText = "Schedule uploaded successfully!";
                        } else {
                            $alertType = "error";
                            $alertTitle = "Database Error";
                            $alertText = "Error: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        $alertType = "error";
                        $alertTitle = "Upload Failed";
                        $alertText = "Error uploading file.";
                    }
                }
            }
        }
        
        // Set sweet alert flag
        $sweetAlert = true;
        
        // Redirect to prevent form resubmission on refresh
        $_SESSION['lab_schedule_alert'] = [
            'type' => $alertType,
            'title' => $alertTitle,
            'text' => $alertText
        ];
        
        header("Location: /login/admin/admin_labsched.php");
        exit();
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
                
                $alertType = "success";
                $alertTitle = "Deleted!";
                $alertText = "Schedule deleted successfully!";
            } else {
                $alertType = "error";
                $alertTitle = "Error";
                $alertText = "Error deleting schedule.";
            }
            $delete_stmt->close();
        }
        $stmt->close();
        
        // Set sweet alert flag
        $sweetAlert = true;
        
        // Redirect to prevent form resubmission on refresh
        $_SESSION['lab_schedule_alert'] = [
            'type' => $alertType,
            'title' => $alertTitle,
            'text' => $alertText
        ];
        
        header("Location: /login/admin/admin_labsched.php");
        exit();
    }
}

// Check if we have a flash message from session
if (isset($_SESSION['lab_schedule_alert'])) {
    $alertType = $_SESSION['lab_schedule_alert']['type'];
    $alertTitle = $_SESSION['lab_schedule_alert']['title'];
    $alertText = $_SESSION['lab_schedule_alert']['text'];
    $sweetAlert = true;
    
    // Clear the session variable after use
    unset($_SESSION['lab_schedule_alert']);
}

// Fetch all schedules for display
$sql = "SELECT * FROM lab_schedules ORDER BY lab_room, upload_date DESC";
$result = $conn->query($sql);
?>