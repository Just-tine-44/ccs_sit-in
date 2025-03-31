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
<body class="bg-gray-100 min-h-screen">
    <!-- Side decoration -->
    <div class="fixed left-0 top-0 h-full w-1/3 admin-gradient hidden lg:block"></div>
    
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left panel with brand info (visible on lg screens) -->
        <div class="lg:w-1/3 admin-gradient text-white p-12 hidden lg:flex lg:flex-col lg:justify-between relative z-10">
            <div>
                <div class="flex items-center mb-8">
                    <img src="../images/ccslogo.png" alt="CCS Logo" class="h-12 w-12 mr-3">
                    <div>
                        <h1 class="text-2xl font-bold">CCS Lab</h1>
                        <p class="text-blue-200">Administration Portal</p>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold leading-tight mb-6">Secure Access to Lab Management System</h2>
                <p class="text-blue-100 mb-8">Monitor and manage all laboratory activities, user data, and system settings from a centralized dashboard.</p>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-700 bg-opacity-50 rounded-full p-2 mr-3">
                            <i class="fas fa-chart-line text-blue-200"></i>
                        </div>
                        <span>Real-time analytics and reporting</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-700 bg-opacity-50 rounded-full p-2 mr-3">
                            <i class="fas fa-users text-blue-200"></i>
                        </div>
                        <span>Complete user management</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-700 bg-opacity-50 rounded-full p-2 mr-3">
                            <i class="fas fa-shield-alt text-blue-200"></i>
                        </div>
                        <span>Advanced security controls</span>
                    </div>
                </div>
            </div>
            
            <div class="text-sm text-blue-200">
                <p>College of Computer Studies &copy; <?php echo date('Y'); ?></p>
                <p>All rights reserved</p>
            </div>
        </div>
        
        <!-- Right content area - login form -->
        <div class="flex-1 flex items-center justify-center px-6 py-12 lg:px-8 relative">
            <!-- Mobile-only back button -->
            <a href="logout_admin.php" class="absolute top-6 left-6 text-gray-600 hover:text-blue-800 transition-colors duration-200 flex items-center gap-2 bg-white py-2 px-4 rounded-lg shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
            </a>
            
            <!-- Mobile-only logo header -->
            <div class="lg:hidden text-center mb-8 flex flex-col items-center">
                <div class="h-16 w-16 rounded-full flex items-center justify-center admin-gradient mb-4">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">CCS Admin Portal</h1>
            </div>
            
            <div class="w-full max-w-md">
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
                            
                            <!-- Remember me row and forgot password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-700 rounded border-gray-300 focus:ring-blue-500">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember session</label>
                                </div>
                            </div>
                            
                            <!-- Login button -->
                            <button type="submit" 
                                class="btn-gradient w-full text-white py-3 px-4 rounded-lg font-medium hover:shadow-lg focus:ring-4 focus:ring-blue-200 transition duration-200 flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Access Control Panel
                            </button>
                        </form>
                    <?php endif; ?>
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
                
                <!-- Footer on mobile -->
                <div class="mt-8 text-center text-gray-500 text-sm lg:hidden">
                    <p>College of Computer Studies &copy; <?php echo date('Y'); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>