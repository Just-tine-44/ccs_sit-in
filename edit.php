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
include 'connection/conn_login.php';

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
        $upload_dir = 'uploadimg/';
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('profile_') . '.' . $file_extension;
        $uploaded_file = $upload_dir . $unique_filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploaded_file)) {
            $profileImg = $uploaded_file;
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
</head>
<body class="bg-gray-100">
    <?php include 'navbarWD.php'; ?>
    <div class="container mx-auto p-6 max-w-5xl">
    <div class="bg-white p-8 rounded-lg shadow flex flex-col md:flex-row gap-8">
        <!-- Left: Profile Picture -->
        <div class="flex flex-col items-center justify-center w-full md:w-1/3">
            <form action="edit.php" method="post" enctype="multipart/form-data" id="profileForm">
                <div class="profile-pic-container mb-4">
                    <img id="preview-image" 
                         src="<?php echo $user['profileImg'] ?? 'images/person.jpg'; ?>" 
                         alt="Profile Picture" 
                         class="w-48 h-48 rounded-full object-cover border">
                </div>
                <div class="text-center mb-6">
                    <label for="profile_picture" class="cursor-pointer inline-block bg-blue-50 text-blue-700 px-4 py-2 rounded-full hover:bg-blue-100 mt-4">
                        Choose New Photo
                    </label>
                    <input type="file" 
                           name="profile_picture" 
                           id="profile_picture" 
                           accept="image/*"
                           class="hidden"
                           onchange="previewImage(this);">
                </div>
            </form>
        </div>

        <!-- Right: Form Inputs -->
        <div class="w-full md:w-2/3">
            <h2 class="text-2xl font-bold mb-6 text-center">Edit Profile</h2>
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold">ID Number:</label>
                        <input type="text" name="idno" value="<?php echo $user['idno']; ?>" class="w-full border px-4 py-2 rounded-full bg-gray-200 border-black" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Course:</label>
                        <input type="text" name="course" value="<?php echo $user['course']; ?>" class="w-full border px-4 py-2 rounded-full bg-gray-200 border-black" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Lastname:</label>
                        <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" class="w-full border px-4 py-2 rounded-full border-black" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Year Level:</label>
                        <input type="text" name="level" value="<?php echo $user['level']; ?>" class="w-full border px-4 py-2 rounded-full bg-gray-200 border-black" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Firstname:</label>
                        <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" class="w-full border px-4 py-2 rounded-full border-black" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Email:</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full border px-4 py-2 rounded-full border-black" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Middlename:</label>
                        <input type="text" name="midname" value="<?php echo $user['midname']; ?>" class="w-full border px-4 py-2 rounded-full border-black" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Address:</label>
                        <input type="text" name="address" value="<?php echo $user['address']; ?>" class="w-full border px-4 py-2 rounded-full border-black" required>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="text-center mt-6">
                    <button type="submit" name="update" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Save Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
</body>
</html>