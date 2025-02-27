<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "./conn_back/logAdm.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/ccslogo.png">
    <title>Admin Login</title>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <a href="../login.php" class="absolute top-4 left-4 bg-blue-500 text-white py-1 px-2 hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">Back</a>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm relative">
        <h2 class="text-2xl font-bold mb-6 text-center"><i class="fas fa-user-shield"></i> Admin</h2>
        <p class="text-red-500 text-center mb-4"><i class="fas fa-exclamation-triangle"></i> Only admin can login</p>
        <form action="admin_login.php" method="POST">
            <div class="mb-4">
                <input type="text" id="username" name="username" placeholder="Username" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <input type="password" id="password" name="password" placeholder="Password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>