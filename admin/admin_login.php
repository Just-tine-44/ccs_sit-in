<?php 
session_start();
// Regular login is handled by logAdm.php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['create_admin'])) {
    include "./conn_back/logAdm.php";
}

if (!isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: ../user/login.php");
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
    <title>Admin Portal | CCS Lab System</title>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .admin-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        }
        .btn-gradient {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.1), 0 8px 10px -6px rgba(30, 58, 138, 0.1);
        }
    </style>
</head>
<body class="bg-blue-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Content area - login form -->
        <div class="w-full max-w-md px-6 py-12">
            <!-- Mobile-only logo header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <div class="h-16 w-16 rounded-full flex items-center justify-center admin-gradient mb-4">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">CCS Admin Portal</h1>
            </div>
            
            <!-- Main authentication card -->
            <div class="bg-white rounded-xl card-shadow p-8 border border-gray-200">
                <!-- Tabs for switching between login and create account -->
                <div class="flex mb-8 bg-gray-100 p-1 rounded-lg">
                    <a href="admin_login.php" class="flex-1 py-2 px-4 text-center font-medium rounded-md text-sm <?php echo !$show_add_admin ? 'bg-white shadow-sm text-blue-800' : 'text-gray-600 hover:text-gray-800'; ?>">
                        <i class="fas fa-sign-in-alt mr-2"></i> Administrator Login
                    </a>
                    <a href="admin_login.php?add_admin=true" class="flex-1 py-2 px-4 text-center font-medium rounded-md text-sm <?php echo $show_add_admin ? 'bg-white shadow-sm text-blue-800' : 'text-gray-600 hover:text-gray-800'; ?>">
                        <i class="fas fa-user-plus mr-2"></i> Create New Admin
                    </a>
                </div>
                
                <?php if($show_add_admin): ?>
                    <!-- Admin creation form -->
                    <form action="conn_back/new_admin.php" method="POST" class="space-y-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Create Administrator</h2>
                            <p class="text-sm text-gray-600 mt-1">Add a new administrative user with system access</p>
                        </div>
                        
                        <!-- Username field -->
                        <div>
                            <label for="new_username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="new_username" name="new_username"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-gray-900" 
                                    placeholder="Admin username"
                                    required>
                            </div>
                        </div>
                        
                        <!-- Password field -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="new_password" name="new_password" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-gray-900" 
                                    placeholder="Secure password"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Password should be at least 8 characters and include a mix of letters and numbers</p>
                        </div>
                        
                        <!-- Create button -->
                        <button type="submit" name="create_admin"
                            class="btn-gradient w-full text-white py-3 px-4 rounded-lg font-medium hover:shadow-lg focus:ring-4 focus:ring-blue-200 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-user-shield mr-2"></i> Create Administrator Account
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Login form -->
                    <form action="admin_login.php" method="POST" class="space-y-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Administrator Login</h2>
                            <p class="text-sm text-gray-600 mt-1">Enter your credentials to access the control panel</p>
                        </div>
                        
                        <!-- Username field -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="username" name="username"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-gray-900" 
                                    placeholder="Admin username"
                                    required>
                            </div>
                        </div>
                        
                        <!-- Password field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="password" name="password" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-gray-900" 
                                    placeholder="Admin password"
                                    required>
                            </div>
                        </div>
                        
                        <!-- Login button -->
                        <button type="submit" 
                            class="btn-gradient w-full text-white py-3 px-4 rounded-lg font-medium hover:shadow-lg focus:ring-4 focus:ring-blue-200 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Access Control Panel
                        </button>
                    </form>
                <?php endif; ?>
                
                <!-- Back to main site link -->
                <div class="mt-6 text-center">
                    <a href="../index.php" class="text-sm text-blue-600 hover:text-blue-800 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to main site
                    </a>
                </div>
            </div>
            
            <!-- Security notice with icon -->
            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-900 flex">
                <div class="flex-shrink-0 mr-3">
                    <i class="fas fa-shield-alt text-blue-500 mt-1"></i>
                </div>
                <div>
                    <strong class="font-medium">Secure access only</strong>
                    <p class="mt-1">This secure portal requires proper authorization. All access attempts are logged for security audit purposes.</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center text-gray-500 text-sm">
                <p>College of Computer Studies &copy; <?php echo date('Y'); ?></p>
            </div>
        </div>
    </div>
</body>
</html>