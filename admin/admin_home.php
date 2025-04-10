<?php 
    session_start();

    if (!isset($_SESSION['admin']) || empty($_SESSION['admin']) || 
    !isset($_SESSION['auth_verified']) || $_SESSION['auth_verified'] !== true) {
    header("Location: ../login.php");
    exit();
    }

    include("navbar_admin.php"); 

    $showAlert = false;
    if (isset($_SESSION['login_success'])) {
        $showAlert = true;
        unset($_SESSION['login_success']); 
    }

    include('./conn_back/postannounce.php');
    include('./conn_back/dashboard_stats.php');
    date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include("icon.php"); ?>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 md:p-6">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Admin Dashboard</h1>
                <p class="text-gray-500">Welcome back, manage your college resources</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Dropdown Menu -->
                <div class="relative">
                    <button id="dashboardOptionsBtn" type="button" class="flex items-center justify-center rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <i class="fas fa-cog mr-2"></i>
                        Options
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="dashboardOptionsMenu" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10">
                        <div class="py-1">
                            <a href="admin_resources.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-book-reader mr-2"></i> Manage Resources
                            </a>
                        </div>
                        <div class="py-1">
                            <a href="admin_points.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-chart-bar mr-2"></i> Usage Points
                            </a>
                        </div>
                        <div class="py-1">
                            <a href="admin_labsched.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-calendar-alt mr-2"></i> Set Lab Schedule
                            </a>
                        </div>
                        <div class="py-1">
                            <a href="admin_leaderboards.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-trophy mr-2"></i> Leaderboards
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Time and Date -->
                <div class="mt-3 md:mt-0">
                    <p class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></p>
                    <p class="text-sm text-gray-500 font-mono" id="running-time"></p>
                </div>
            </div>

            <script>
            
            // Dropdown toggle
            document.getElementById('dashboardOptionsBtn').addEventListener('click', function(event) {
                // Prevent event from propagating to the document
                event.stopPropagation();
                
                // Toggle the 'hidden' class
                const dropdownMenu = document.getElementById('dashboardOptionsMenu');
                dropdownMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdownMenu = document.getElementById('dashboardOptionsMenu');
                const dropdownButton = document.getElementById('dashboardOptionsBtn');
                
                // Check if the click is outside the dropdown and button
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });

            function updateTime() {
                const now = new Date();
                let hours = now.getHours();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                
                document.getElementById('running-time').textContent = 
                    `${hours}:${minutes}:${seconds} ${ampm}`;
            }

            // Update immediately, then every second
            updateTime();
            setInterval(updateTime, 1000);
            </script>
            <script>
            function updateDate() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
            }

            updateDate();
            setInterval(updateDate, 60000);
            
            // Dropdown toggle
            document.getElementById('dashboardOptionsBtn').addEventListener('click', function() {
                document.getElementById('dashboardOptionsMenu').classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('dashboardOptionsMenu');
                const button = document.getElementById('dashboardOptionsBtn');
                if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
            </script>
        </div>
        
        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Students Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo number_format($dashboardStats['total_students']); ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-user-graduate text-blue-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <?php if ($dashboardStats['student_percent_change'] >= 0): ?>
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $dashboardStats['student_percent_change']; ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-red-500 text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i><?php echo abs($dashboardStats['student_percent_change']); ?>%
                        </span>
                    <?php endif; ?>
                    <span class="text-gray-500 text-sm ml-2">Since last month</span>
                </div>
            </div>
            
            <!-- Currently Sit-in Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Currently Sit-in</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo number_format($dashboardStats['active_sit_ins']); ?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-chair text-green-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <?php if ($dashboardStats['active_percent_change'] >= 0): ?>
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $dashboardStats['active_percent_change']; ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-red-500 text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i><?php echo abs($dashboardStats['active_percent_change']); ?>%
                        </span>
                    <?php endif; ?>
                    <span class="text-gray-500 text-sm ml-2">From yesterday</span>
                </div>
            </div>
            
            <!-- Total Sit-in Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Sit-in</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo number_format($dashboardStats['total_sit_ins']); ?></p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-history text-yellow-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <?php if ($dashboardStats['semester_percent_change'] >= 0): ?>
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $dashboardStats['semester_percent_change']; ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-red-500 text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i><?php echo abs($dashboardStats['semester_percent_change']); ?>%
                        </span>
                    <?php endif; ?>
                    <span class="text-gray-500 text-sm ml-2">This semester</span>
                </div>
            </div>
            
            <!-- Sessions Today Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sessions Today</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo number_format($dashboardStats['sessions_today']); ?></p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-calendar-day text-purple-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <?php if ($dashboardStats['today_percent_change'] >= 0): ?>
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $dashboardStats['today_percent_change']; ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-red-500 text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i><?php echo abs($dashboardStats['today_percent_change']); ?>%
                        </span>
                    <?php endif; ?>
                    <span class="text-gray-500 text-sm ml-2">From yesterday</span>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Charts Section (2 columns) -->
            <div class="lg:col-span-2 grid grid-cols-1 gap-6">
                <!-- Student Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-gray-800 text-lg">Student Distribution</h2>
                        <div class="flex space-x-2">
                            <button id="weekly-btn" class="distribution-filter px-3 py-1 text-sm rounded-md bg-blue-50 text-blue-600 font-medium" data-period="weekly">Weekly</button>
                            <button id="monthly-btn" class="distribution-filter px-3 py-1 text-sm rounded-md text-gray-500 hover:bg-gray-100" data-period="monthly">Monthly</button>
                            <button id="yearly-btn" class="distribution-filter px-3 py-1 text-sm rounded-md text-gray-500 hover:bg-gray-100" data-period="yearly">Yearly</button>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="studentYearLevelChart"></canvas>
                    </div>
                </div>
                
                <!-- Statistics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pie Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 text-lg mb-4">Student Categories</h2>
                        <div class="h-60">
                            <canvas id="pieChart"></canvas>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <div class="flex items-center" title="College of Computer Studies">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-xs text-gray-600">CCS</span>
                            </div>
                            <div class="flex items-center" title="College of Engineering">
                                <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-xs text-gray-600">COE</span>
                            </div>
                            <div class="flex items-center" title="College of Education">
                                <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                <span class="text-xs text-gray-600">COED</span>
                            </div>
                            <div class="flex items-center" title="College of Business Administration">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-xs text-gray-600">CBA</span>
                            </div>
                            <div class="flex items-center" title="College of Arts and Social Sciences">
                                <div class="w-3 h-3 rounded-full bg-purple-500 mr-2"></div>
                                <span class="text-xs text-gray-600">CASS</span>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Session Usage -->
                    <!-- Session Usage (Redesigned) -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-5 pb-3 flex justify-between items-center border-b border-gray-100">
                            <h2 class="font-bold text-gray-800 text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Session Usage
                            </h2>
                            <div class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-full">
                                This month
                            </div>
                        </div>
                        
                        <div class="p-5 space-y-6">
                            <!-- Morning Sessions -->
                            <div class="relative overflow-hidden">
                                <div class="flex justify-between items-center mb-1.5">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 mr-2 flex-shrink-0"></div>
                                        <h3 class="font-medium text-gray-700 flex items-center text-sm">
                                            Morning
                                            <span class="ml-2 text-xs text-gray-400">(6AM - 12PM)</span>
                                        </h3>
                                    </div>
                                    <span class="text-sm font-semibold text-blue-600"><?php echo $dashboardStats['sessionUsage']['Morning']['percentage'] ?? 0; ?>%</span>
                                </div>
                                
                                <div class="relative">
                                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-500 transform origin-left scale-x-100" 
                                            style="width: <?php echo $dashboardStats['sessionUsage']['Morning']['percentage'] ?? 0; ?>%">
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-full opacity-10 flex items-center justify-between px-1 pointer-events-none">
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex justify-between text-xs text-gray-400">
                                    <div>0</div>
                                    <div><?php echo $dashboardStats['sessionUsage']['Morning']['count'] ?? 0; ?> sessions</div>
                                </div>
                            </div>
                            
                            <!-- Afternoon Sessions -->
                            <div class="relative overflow-hidden">
                                <div class="flex justify-between items-center mb-1.5">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2 flex-shrink-0"></div>
                                        <h3 class="font-medium text-gray-700 flex items-center text-sm">
                                            Afternoon
                                            <span class="ml-2 text-xs text-gray-400">(12PM - 6PM)</span>
                                        </h3>
                                    </div>
                                    <span class="text-sm font-semibold text-green-600"><?php echo $dashboardStats['sessionUsage']['Afternoon']['percentage'] ?? 0; ?>%</span>
                                </div>
                                
                                <div class="relative">
                                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full transition-all duration-500 transform origin-left scale-x-100" 
                                            style="width: <?php echo $dashboardStats['sessionUsage']['Afternoon']['percentage'] ?? 0; ?>%">
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-full opacity-10 flex items-center justify-between px-1 pointer-events-none">
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex justify-between text-xs text-gray-400">
                                    <div>0</div>
                                    <div><?php echo $dashboardStats['sessionUsage']['Afternoon']['count'] ?? 0; ?> sessions</div>
                                </div>
                            </div>
                            
                            <!-- Evening Sessions -->
                            <div class="relative overflow-hidden">
                                <div class="flex justify-between items-center mb-1.5">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-purple-500 mr-2 flex-shrink-0"></div>
                                        <h3 class="font-medium text-gray-700 flex items-center text-sm">
                                            Evening
                                            <span class="ml-2 text-xs text-gray-400">(6PM - 10PM)</span>
                                        </h3>
                                    </div>
                                    <span class="text-sm font-semibold text-purple-600"><?php echo $dashboardStats['sessionUsage']['Evening']['percentage'] ?? 0; ?>%</span>
                                </div>
                                
                                <div class="relative">
                                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full transition-all duration-500 transform origin-left scale-x-100" 
                                            style="width: <?php echo $dashboardStats['sessionUsage']['Evening']['percentage'] ?? 0; ?>%">
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-full opacity-10 flex items-center justify-between px-1 pointer-events-none">
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex justify-between text-xs text-gray-400">
                                    <div>0</div>
                                    <div><?php echo $dashboardStats['sessionUsage']['Evening']['count'] ?? 0; ?> sessions</div>
                                </div>
                            </div>
                            
                            <!-- Weekend Sessions -->
                            <div class="relative overflow-hidden">
                                <div class="flex justify-between items-center mb-1.5">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2 flex-shrink-0"></div>
                                        <h3 class="font-medium text-gray-700 flex items-center text-sm">
                                            Weekend
                                            <span class="ml-2 text-xs text-gray-400">(Sat & Sun)</span>
                                        </h3>
                                    </div>
                                    <span class="text-sm font-semibold text-yellow-600"><?php echo $dashboardStats['sessionUsage']['Weekend']['percentage'] ?? 0; ?>%</span>
                                </div>
                                
                                <div class="relative">
                                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full transition-all duration-500 transform origin-left scale-x-100" 
                                            style="width: <?php echo $dashboardStats['sessionUsage']['Weekend']['percentage'] ?? 0; ?>%">
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-full opacity-10 flex items-center justify-between px-1 pointer-events-none">
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                        <div class="w-0.5 h-1/2 bg-gray-400"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex justify-between text-xs text-gray-400">
                                    <div>0</div>
                                    <div><?php echo $dashboardStats['sessionUsage']['Weekend']['count'] ?? 0; ?> sessions</div>
                                </div>
                            </div>
                            
                            <div class="pt-2 mt-2 border-t border-gray-100 flex justify-between items-center text-sm">
                                <span class="text-gray-500">Total sessions:</span>
                                <span class="font-semibold text-gray-800">
                                    <?php
                                        $total = 0;
                                        if (isset($dashboardStats['sessionUsage'])) {
                                            foreach ($dashboardStats['sessionUsage'] as $session) {
                                                $total += isset($session['count']) ? $session['count'] : 0;
                                            }
                                        }
                                        echo $total;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Announcements Section -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-lg">Announcements</h2>
                </div>
                <div class="p-5">
                    <form action="admin_home.php" method="POST">
                        <div class="relative">
                            <textarea name="message" class="w-full border rounded-lg p-3 text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your announcement here..." rows="4" required></textarea>
                            <input type="hidden" name="new_announcement" value="1">
                        </div>
                        <div class="flex justify-end mt-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300 flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i> Post Announcement
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-5">
                        <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-bullhorn mr-2 text-blue-500"></i> Posted Announcements
                        </h3>
                        
                        <?php if (empty($announcements)): ?>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <i class="fas fa-info-circle text-blue-400 text-2xl mb-2"></i>
                                <p class="text-gray-500">No announcements available at the moment.</p>
                            </div>
                        <?php else: ?>
                            <div class="announcements-container overflow-y-auto custom-scrollbar pr-1" id="announcementScroll">
                                <?php foreach ($announcements as $announcement): 
                                    // Properly escape messages for both HTML and JavaScript
                                    $safe_id = htmlspecialchars($announcement['announcement_id'] ?? '');
                                    $safe_message = htmlspecialchars($announcement['message'] ?? '', ENT_QUOTES);
                                    $safe_js_message = addslashes(htmlspecialchars($announcement['message'] ?? '', ENT_QUOTES));
                                ?>
                                    <div class="mb-4 p-3 border-l-4 border-blue-400 bg-blue-50 rounded-r-lg">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center text-xs text-gray-500 mb-1">
                                                <span class="font-medium text-blue-600"><?php echo htmlspecialchars($announcement['admin_name'] ?? ''); ?></span>
                                                <span class="mx-2">•</span>
                                                <span><?php echo date('M d, Y h:i A', strtotime($announcement['post_date'] ?? '')); ?></span>
                                            </div>
                                            <div class="flex space-x-1">
                                                <button class="text-gray-400 hover:text-yellow-500 transition-colors" onclick="editAnnouncement('<?php echo $safe_id; ?>', '<?php echo $safe_js_message; ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-gray-400 hover:text-red-500 transition-colors" onclick="deleteAnnouncement('<?php echo $safe_id; ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 text-sm"><?php echo $safe_message; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden animate__animated animate__fadeInUp">
            <div class="px-5 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="font-bold text-gray-800">Edit Announcement</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST" action="admin_home.php" class="p-5">
                <textarea id="editMessage" name="edit_message" class="w-full border rounded-lg p-3 text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="6" required></textarea>
                <input type="hidden" id="editId" name="edit_id">
                <div class="flex justify-end mt-4 space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if ($showAlert): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true, 
                    position: 'top-start', 
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Welcome back, Admin!',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: 'center',
                    icon: '<?php echo htmlspecialchars($_SESSION['alert']['icon']); ?>',
                    title: '<?php echo htmlspecialchars($_SESSION['alert']['title']); ?>',
                    text: '<?php echo htmlspecialchars($_SESSION['alert']['text']); ?>',
                    showConfirmButton: false,
                    timer: 2000,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut'
                    }
                }).then(function() {
                    window.location = '<?php echo htmlspecialchars($_SESSION['alert']['redirect']); ?>';
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Student Categories Pie Chart
        const ctx = document.getElementById('pieChart');
        if (ctx) {
            const collegeData = <?php echo json_encode($dashboardStats['collegeData'] ?? []); ?>;
            
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: collegeData.labels || ['CCS', 'COE', 'COED', 'CBA', 'CASS'],
                    datasets: [{
                        data: collegeData.data || [30, 25, 15, 20, 10],
                        backgroundColor: collegeData.colors || ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6'],
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const dataset = context.dataset;
                                    const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                    const value = dataset.data[context.dataIndex];
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }

        // Student Year Level Chart
        // Student Distribution Chart variables
        let studentDistributionChart;
        const chartData = <?php echo json_encode($dashboardStats['studentDistribution'] ?? []); ?>;
        let currentPeriod = 'weekly'; // Default period

        function updateDistributionChart(period) {
            // Get the relevant data for the selected period
            const data = chartData[period] || { 
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                data: [12, 19, 15, 25, 22, 14, 7]  // Fallback demo data
            };
            
            // Update button styling
            document.querySelectorAll('.distribution-filter').forEach(btn => {
                if (btn.dataset.period === period) {
                    btn.classList.add('bg-blue-50', 'text-blue-600');
                    btn.classList.remove('text-gray-500', 'hover:bg-gray-100');
                } else {
                    btn.classList.remove('bg-blue-50', 'text-blue-600');
                    btn.classList.add('text-gray-500', 'hover:bg-gray-100');
                }
            });
            
            // If chart already exists, destroy it first
            if (studentDistributionChart) {
                studentDistributionChart.destroy();
            }
            
            // Create a new chart with the updated data
            const ctx = document.getElementById('studentYearLevelChart').getContext('2d');
            
            studentDistributionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Sit-in Sessions',
                        data: data.data || [],
                        backgroundColor: [
                            'rgba(79, 70, 229, 0.7)',
                            'rgba(79, 70, 229, 0.6)',
                            'rgba(79, 70, 229, 0.5)',
                            'rgba(79, 70, 229, 0.4)',
                            'rgba(79, 70, 229, 0.3)',
                            'rgba(79, 70, 229, 0.2)',
                            'rgba(79, 70, 229, 0.1)'
                        ],
                        borderWidth: 0,
                        borderRadius: 4,
                        barThickness: period === 'yearly' ? 16 : 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return tooltipItems[0].label;
                                },
                                label: function(context) {
                                    return context.parsed.y + ' sit-in sessions';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }

        // Add event listeners for period filters
        document.getElementById('weekly-btn').addEventListener('click', function() {
            updateDistributionChart('weekly');
        });

        document.getElementById('monthly-btn').addEventListener('click', function() {
            updateDistributionChart('monthly');
        });

        document.getElementById('yearly-btn').addEventListener('click', function() {
            updateDistributionChart('yearly');
        });

        // Initialize the chart with weekly data
        updateDistributionChart('weekly');

        // Custom scrollbar behavior
        const announcementScroll = document.getElementById('announcementScroll');
        if (announcementScroll) {
            announcementScroll.addEventListener('scroll', function() {
                announcementScroll.classList.add('scrolling');
                clearTimeout(announcementScroll.scrollTimeout);
                announcementScroll.scrollTimeout = setTimeout(function() {
                    announcementScroll.classList.remove('scrolling');
                }, 500);
            });
        }
    });
    
    // Announcement functions
    function editAnnouncement(id, message) {
        document.getElementById('editId').value = id;
        document.getElementById('editMessage').value = message;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'Delete Announcement?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            heightAuto: false,
            customClass: {
                confirmButton: 'px-4 py-2 text-sm font-medium',
                cancelButton: 'px-4 py-2 text-sm font-medium'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin_home.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_id';
                input.value = id;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    </script>

    <style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        transition: background 0.3s ease-in-out;
    }

    .custom-scrollbar.scrolling::-webkit-scrollbar-thumb {
        background: #3b82f6;
    }

    .announcements-container {
    max-height: 530px; 
    }
    </style>
</body>
</html>