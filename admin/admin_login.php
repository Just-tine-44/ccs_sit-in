<?php 
session_start();
// Regular login is handled by logAdm.php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['create_admin'])) {
    include "./conn_back/logAdm.php";
}

if (!isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Handle showing add admin form
$show_add_admin = isset($_GET['add_admin']) && $_GET['add_admin'] == 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/ccslogo.png">
    <title>Admin Login | CCS Lab System</title>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <!-- Back button (simple) -->
    <a href="logout_admin.php" class="absolute top-6 left-6 text-gray-500 hover:text-gray-700 flex items-center gap-2">
        <i class="fas fa-home"></i>
        <span>Back to Main</span>
    </a>
    
    <!-- Clean, minimal container -->
    <div class="w-full max-w-sm bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <!-- Simple header -->
        <div class="py-6 px-6 text-center border-b border-gray-100">
            <div class="mb-3 inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-500 rounded-full">
                <i class="fas fa-user-shield text-xl"></i>
            </div>
            <h1 class="text-xl font-semibold text-gray-800">
                <?php echo $show_add_admin ? 'Create Admin Account' : 'Admin Login'; ?>
            </h1>
            <p class="text-sm text-gray-500 mt-1">College of Computer Studies</p>
        </div>
        
        <!-- Form container with subtle styling -->
        <div class="p-6">
            <?php if($show_add_admin): ?>
                <!-- Admin creation form - points to new_admin.php -->
                <form action="conn_back/new_admin.php" method="POST" class="space-y-4">
                    <!-- Username field -->
                    <div>
                        <label for="new_username" class="block text-sm font-medium text-gray-700 mb-1">New Username</label>
                        <input type="text" id="new_username" name="new_username"
                            class="w-full border-0 bg-gray-50 px-3 py-2.5 rounded text-gray-900 focus:ring-1 focus:ring-blue-500" 
                            required>
                    </div>
                    
                    <!-- Password field -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="new_password" name="new_password" 
                            class="w-full border-0 bg-gray-50 px-3 py-2.5 rounded text-gray-900 focus:ring-1 focus:ring-blue-500" 
                            required>
                    </div>
                    
                    <!-- Submit button -->
                    <button type="submit" name="create_admin"
                        class="w-full bg-blue-500 text-white py-2.5 px-4 rounded font-medium hover:bg-blue-600 transition duration-200 mt-2">
                        Create Admin
                    </button>
                    
                    <!-- Back to login link -->
                    <div class="text-center mt-4">
                        <a href="admin_login.php" class="text-sm text-gray-500 hover:text-blue-500">
                            Back to Login
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <!-- Login form -->
                <form action="admin_login.php" method="POST" class="space-y-4">
                    <!-- Username field (minimal) -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="username" name="username"
                            class="w-full border-0 bg-gray-50 px-3 py-2.5 rounded text-gray-900 focus:ring-1 focus:ring-blue-500" 
                            required>
                    </div>
                    
                    <!-- Password field (minimal) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" 
                            class="w-full border-0 bg-gray-50 px-3 py-2.5 rounded text-gray-900 focus:ring-1 focus:ring-blue-500" 
                            required>
                    </div>
                    
                    <!-- Simple login button -->
                    <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2.5 px-4 rounded font-medium hover:bg-blue-600 transition duration-200 mt-2">
                        Sign In
                    </button>
                    
                    <!-- Add admin link -->
                    <div class="text-center mt-4">
                        <a href="admin_login.php?add_admin=true" class="text-sm text-gray-500 hover:text-blue-500">
                            <i class="fas fa-user-plus text-xs mr-1"></i> Add New Admin
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Minimal footer -->
    <div class="absolute bottom-4 text-center text-gray-400 text-xs">
        <p>CCS Lab System &copy; 2025</p>
    </div>
</body>
</html>