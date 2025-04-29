<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];


// Include the connection file
include '../connection/conn_login.php';

// Update profile functionality
if (isset($_POST['update'])) {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $profileImg = isset($user['profileImg']) ? $user['profileImg'] : 'images/person.jpg';

    $changes_made = false;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploadimg/';
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('profile_') . '.' . $file_extension;
        $uploaded_file = $upload_dir . $unique_filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploaded_file)) {
            $profileImg = str_replace('../', '', $uploaded_file); 
            $changes_made = true;
        }
    }

    // Check if any field has changed
    if ($lastname != $user['lastname'] || 
        $firstname != $user['firstname'] || 
        $midname != $user['midname'] || 
        $email != $user['email'] || 
        $address != $user['address'] ||
        $changes_made) {

        $stmt = $conn->prepare("UPDATE users SET lastname=?, firstname=?, midname=?, email=?, address=?, profileImg=? WHERE idno=?");
        $stmt->bind_param("sssssss", $lastname, $firstname, $midname, $email, $address, $profileImg, $user['idno']);
        $result = $stmt->execute();

        if ($result) {
            // Update session data
            $_SESSION['user']['lastname'] = $lastname;
            $_SESSION['user']['firstname'] = $firstname;
            $_SESSION['user']['midname'] = $midname;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['profileImg'] = $profileImg;

            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Profile updated successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'homepage.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Profile update failed'
                    });
                });
            </script>";
        }
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'No changes made',
                    text: 'You didn\'t make any changes'
                }).then(() => {
                    window.location.href = 'homepage.php';
                });
            });
        </script>";
    }
}
?>

<style>
        .profile-pic-container {
            position: relative;
            width: 200px;
            height: 200px;
            cursor: pointer;
            margin: auto;
        }
        .profile-pic-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-pic-container input[type="file"] {
            display: none;
        }
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>