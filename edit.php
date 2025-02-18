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
    $profileImg = isset($user['profileImg']) ? $user['profileImg'] : 'images/person.jpg'; // Default to existing profile picture

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploadimg/';
        $uploaded_file = $upload_dir . basename($_FILES['profile_picture']['name']);
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploaded_file)) {
            $profileImg = $uploaded_file;
        }
    }

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

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        if ($stmt->affected_rows > 0) {
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
                    icon: 'info',
                    title: 'No changes made',
                    text: 'You didn\'t make any changes'
                }).then(() => {
                    window.location.href = 'edit.php';
                });
                });
              </script>";
        }
        exit();
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
</head>
<body class="bg-gray-100">
<?php include 'navbarWD.php'; ?>
<div class="container mx-auto p-4 max-w-4xl">
    <div class="bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-4 text-center">Edit Profile</h2>
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">ID Number</label>
                    <input type="text" name="idno" value="<?php echo $user['idno']; ?>" class="w-full px-4 py-2 border rounded-lg bg-gray-200" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Lastname</label>
                    <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Firstname</label>
                    <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Middlename</label>
                    <input type="text" name="midname" value="<?php echo $user['midname']; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Course</label>
                    <input type="text" name="course" value="<?php echo $user['course']; ?>" class="w-full px-4 py-2 border rounded-lg bg-gray-200" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Year Level</label>
                    <input type="text" name="level" value="<?php echo $user['level']; ?>" class="w-full px-4 py-2 border rounded-lg bg-gray-200" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Address</label>
                    <input type="text" name="address" value="<?php echo $user['address']; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Profile Picture</label>
                    <input type="file" name="profile_picture" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="text-center">
                    <button type="submit" name="update" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save Info</button>
                </div>
            </form>
        </div>
        <div class="flex justify-center items-center">
            <img src="images/profile.jpg" alt="Profile Picture" class="rounded-lg w-full h-auto">
        </div>
    </div>
</div>
</body>
</html>