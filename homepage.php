<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
    $user = $_SESSION['user'];
    $profileImg = isset($user['profileImg']) ? $user['profileImg'] : 'images/person.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="script.js"></script>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
<?php include 'navbar.php'; ?>
<div class="container mx-auto p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2 text-center bg-blue-500 text-white p-2 rounded">Student Information</h2>
        <div class="border-b-2 border-black pb-4 mb-4">
            <img src="<?php echo $profileImg; ?>" alt="Profile Pic" class="w-32 h-32 mx-auto rounded-full">
        </div>
        <p class="text-gray-700 mb-2"><i class="fas fa-user"></i> Name: <?php echo $user['firstname'] . ' ' . $user['midname'] . ' ' . $user['lastname']; ?></p>
        <p class="text-gray-700 mb-2"><i class="fas fa-book"></i> Course: <?php echo $user['course']; ?></p>
        <p class="text-gray-700 mb-2"><i class="fas fa-calendar"></i> Year: <?php echo $user['level']; ?></p>
        <p class="text-gray-700 mb-2"><i class="fas fa-envelope"></i> Email: <?php echo $user['email']; ?></p>
        <p class="text-gray-700 mb-2"><i class="fas fa-home"></i> Address: <?php echo $user['address']; ?></p>
        <p class="text-gray-700 mb-2"><i class="fas fa-clock"></i> Session:</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2 text-center bg-blue-500 text-white p-2 rounded">
            <i class="fas fa-bullhorn"></i> Announcements
        </h2>
        <p class="text-gray-700">No new announcements.</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2 text-center bg-blue-500 text-white p-2 rounded">Rules and Regulations</h2>
        <p class="text-gray-700">1. Attend all classes.</p>
        <p class="text-gray-700">2. Submit assignments on time.</p>
        <p class="text-gray-700">3. Maintain discipline.</p>
    </div>
</div>
</body>
</html>