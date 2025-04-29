<?php 
    include("../connection/profile_edit.php");
    $stud_session = isset($_SESSION['stud_session']) ? $_SESSION['stud_session'] : ['session' => 'N/A'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        
        .profile-gradient {
            background: linear-gradient(120deg, #e0f2fe, #dbeafe, #e0e7ff);
        }
        
        .custom-shadow {
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 8px 10px -5px rgba(59, 130, 246, 0.04);
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        
        .profile-pic-upload-overlay {
            opacity: 0;
            transition: all 0.3s ease;
            background: rgba(37, 99, 235, 0.8);
        }
        
        .profile-pic-container:hover .profile-pic-upload-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-10 max-w-5xl">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-500 mt-2">Update your personal information and profile picture</p>
        </div>
        
        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-lg custom-shadow overflow-hidden">
            <!-- Profile Header -->
            <div class="profile-gradient p-6 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-2">
                        <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-semibold text-gray-800">Edit Profile Information</h2>
                        <p class="text-sm text-gray-500">Make changes to your profile and save when done</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <div class="flex items-center px-4 py-2 bg-blue-50 rounded-full text-blue-700 text-sm">
                        <i class="fas fa-ticket-alt mr-2"></i>
                        <span class="font-medium">Remaining Sessions: <?php echo $stud_session['session']; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Form Content -->
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-10">
                        <!-- Left: Profile Picture -->
                        <div class="md:w-1/3 flex flex-col items-center">
                            <div class="profile-pic-container relative group mb-6 mt-4">
                                <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-white shadow-md mx-auto">
                                    <img id="preview-image" 
                                        src="<?php 
                                            if (!empty($user['profileImg'])) {
                                                echo strpos($user['profileImg'], '../') === 0 ? $user['profileImg'] : '../' . $user['profileImg'];
                                            } else {
                                                echo '../images/person.jpg';
                                            }
                                        ?>" 
                                        alt="Profile Picture" 
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="absolute inset-0 profile-pic-upload-overlay rounded-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-camera text-2xl mb-2"></i>
                                    <span class="font-medium text-sm">Change Photo</span>
                                </div>
                                <label for="profile_picture" class="absolute inset-0 cursor-pointer">
                                    <span class="sr-only">Choose New Photo</span>
                                </label>
                                <input type="file" 
                                       name="profile_picture" 
                                       id="profile_picture" 
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(this);">
                            </div>
                            <div class="text-center space-y-2">
                                <h3 class="font-medium text-lg text-gray-800">
                                    <?php echo $user['firstname'] . ' ' . $user['lastname']; ?>
                                </h3>
                                <p class="text-blue-600 font-medium"><?php echo $user['idno']; ?></p>
                                <div class="flex justify-center mt-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-graduation-cap mr-1"></i> <?php echo $user['course']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 w-full bg-gradient-to-r from-gray-50 to-white rounded-lg p-3.5 shadow-sm border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 mr-2">
                                            <i class="fas fa-ticket-alt text-blue-500 text-xs"></i>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600">Remaining Sessions</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="font-semibold text-blue-600"><?php echo $stud_session['session']; ?></span>
                                        <span class="text-gray-400 text-xs ml-0.5">/30</span>
                                    </div>
                                </div>
                                
                                <div class="relative w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <?php 
                                        $max_sessions = 30;
                                        $current = intval($stud_session['session']);
                                        $percentage = min(100, ($current / $max_sessions) * 100);
                                        
                                        // Determine color based on remaining sessions
                                        if ($percentage > 60) {
                                            $gradient = 'from-blue-400 to-blue-500';
                                        } else if ($percentage > 30) {
                                            $gradient = 'from-yellow-400 to-yellow-500';
                                        } else {
                                            $gradient = 'from-red-400 to-red-500';
                                        }
                                    ?>
                                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r <?php echo $gradient; ?> rounded-full transition-all duration-500" 
                                        style="width: <?php echo $percentage; ?>%">
                                        <div class="absolute top-0 right-0 bottom-0 w-1.5 bg-white opacity-30 rounded-full"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between mt-1.5">
                                    <div class="flex items-center text-xs text-gray-400">
                                        <?php if ($percentage < 30): ?>
                                            <i class="fas fa-exclamation-circle text-red-500 mr-1"></i> Running low
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-xs font-medium">
                                        <?php
                                            if ($percentage > 60) echo '<span class="text-blue-500">Good</span>';
                                            else if ($percentage > 30) echo '<span class="text-yellow-500">Medium</span>';
                                            else echo '<span class="text-red-500">Low</span>';
                                        ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mt-6 w-full">
                                <a href="password_edit.php" class="group relative flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-blue-100 w-full">
                                    <div class="absolute inset-0 w-3 bg-gradient-to-r from-blue-500 to-indigo-500 transform -skew-x-12 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                                    <div class="relative flex items-center">
                                        <span class="flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white mr-3 shadow-inner">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <span>
                                            <span class="block font-medium">Change Password</span>
                                            <span class="text-xs text-blue-600 opacity-80">Secure your account</span>
                                        </span>
                                    </div>
                                    <i class="fas fa-chevron-right ml-auto text-blue-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Right: Form Fields -->
                        <div class="md:w-2/3">
                            <div class="bg-gray-50 p-5 rounded-xl mb-6">
                                <h3 class="text-sm font-medium text-gray-600 mb-3">ACCOUNT INFORMATION</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">ID Number</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-id-card text-gray-400"></i>
                                            </div>
                                            <input type="text" name="idno" value="<?php echo $user['idno']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Course</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-graduation-cap text-gray-400"></i>
                                            </div>
                                            <input type="text" name="course" value="<?php echo $user['course']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Year Level</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-layer-group text-gray-400"></i>
                                            </div>
                                            <input type="text" name="level" value="<?php echo $user['level']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </div>
                                            <input type="email" name="email" value="<?php echo $user['email']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-5 rounded-xl">
                                <h3 class="text-sm font-medium text-gray-600 mb-3">PERSONAL INFORMATION</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Last Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                            <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">First Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                            <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Middle Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                            <input type="text" name="midname" value="<?php echo $user['midname']; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Address</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-home text-gray-400"></i>
                                            </div>
                                            <input type="text" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" 
                                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer with Save Button -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button type="submit" name="update" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center text-gray-500 text-xs mt-6">
            Last updated: <?php echo date('F j, Y'); ?>
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