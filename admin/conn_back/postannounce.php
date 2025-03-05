<?php 
    include(__DIR__ . '/../../conn/dbcon.php'); // Adjust the path as necessary

    function showAlert($icon, $title, $text, $redirect = 'admin_home.php') {
        $_SESSION['alert'] = [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
            'redirect' => $redirect
        ];
    }

    // Error handling for database connection
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Handle form submission for new announcement
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            if (isset($_POST['new_announcement'])) {
                $message = trim($_POST['message']);
                
                // Validate message
                if (empty($message)) {
                    showAlert('warning', 'Invalid Input', 'Announcement message cannot be empty.');
                    exit();
                }

                $admin_name = 'CCS-Admin'; // Default admin name

                $stmt = $conn->prepare("INSERT INTO announcements (admin_name, message) VALUES (?, ?)");
                $stmt->bind_param("ss", $admin_name, $message);
                if ($stmt->execute()) {
                    showAlert('success', 'Announcement Posted', 'Your announcement has been posted successfully!');
                } else {
                    showAlert('error', 'Error', 'There was an error posting your announcement. Please try again.');
                }
                $stmt->close();
            }
            
            // Handle edit announcement
            if (isset($_POST['edit_id'])) {
                $edit_id = filter_input(INPUT_POST, 'edit_id', FILTER_VALIDATE_INT);
                $edit_message = trim($_POST['edit_message']);
                
                // Validate inputs
                if ($edit_id === false || empty($edit_message)) {
                    showAlert('warning', 'Invalid Input', 'Please provide a valid announcement and ID.');
                    exit();
                }

                $stmt = $conn->prepare("UPDATE announcements SET message = ? WHERE announcement_id = ?");
                $stmt->bind_param("si", $edit_message, $edit_id);
                if ($stmt->execute()) {
                    showAlert('success', 'Announcement Edited', 'Your announcement has been edited successfully!');
                } else {
                    showAlert('error', 'Error', 'There was an error editing your announcement. Please try again.');
                }
                $stmt->close();
            }

            // Handle delete announcement
            if (isset($_POST['delete_id'])) {
                $delete_id = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
                
                // Validate input
                if ($delete_id === false) {
                    showAlert('warning', 'Invalid Input', 'Invalid announcement ID.');
                    exit();
                }

                $stmt = $conn->prepare("DELETE FROM announcements WHERE announcement_id = ?");
                $stmt->bind_param("i", $delete_id);
                if ($stmt->execute()) {
                    showAlert('success', 'Announcement Deleted', 'Your announcement has been deleted successfully!');
                } else {
                    showAlert('error', 'Error', 'There was an error deleting your announcement. Please try again.');
                }
                $stmt->close();
            }
        } catch (Exception $e) {
            // Log the error and show a generic error message
            error_log('Announcement Error: ' . $e->getMessage());
            showAlert('error', 'System Error', 'An unexpected error occurred. Please try again.');
        }
    }

    // Fetch announcements from the database
    $announcements = [];
    try {
        $result = $conn->query("SELECT announcement_id, admin_name, message, post_date FROM announcements ORDER BY post_date DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $announcements[] = $row;
            }
            $result->free();
        } else {
            // Log the error
            error_log('Announcement Fetch Error: ' . $conn->error);
        }
    } catch (Exception $e) {
        error_log('Announcement Fetch Exception: ' . $e->getMessage());
    }
?>
    <style>
    /* Default Gray Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    /* Default Track */
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #d1d5db; /* Gray background */
        border-radius: 10px;
    }

    /* Default Thumb (Gray) */
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #9ca3af; /* Default gray */
        border-radius: 10px;
        transition: background 0.5s ease-in-out;
    }

    /* Active Thumb (Blue Gradient) */
    .custom-scrollbar.scrolling::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #3b82f6, #1e40af); /* Blue gradient */
    }
    </style>