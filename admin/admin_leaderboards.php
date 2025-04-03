<?php
// Start the session to ensure we have access to session variables
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

// Include the database connection
include('../conn/dbcon.php');

// Query to get top 5 students with the most sit-in sessions
$query = "SELECT u.id, CONCAT(u.firstname, ' ', u.lastname) as name, u.email, u.level, u.course, u.profileImg, 
         COUNT(s.sit_in_id) as session_count,
         SUM(TIMESTAMPDIFF(MINUTE, s.check_in_time, s.check_out_time)) as total_minutes
         FROM users u
         JOIN curr_sit_in s ON u.id = s.user_id
         WHERE s.status = 'completed'
         GROUP BY u.id
         ORDER BY session_count DESC, total_minutes DESC
         LIMIT 5";

$result = $conn->query($query);
$topStudents = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $topStudents[] = $row;
    }
}

// Query to get total number of sit-in sessions
$statsQuery = "SELECT 
    COUNT(*) as total_sessions,
    COUNT(DISTINCT user_id) as unique_students,
    SUM(TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time)) as total_hours
    FROM curr_sit_in
    WHERE status = 'completed'";
    
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Calculate total hours from minutes
$totalHours = round($stats['total_hours'] / 60, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Leaderboards | Admin</title>
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="admin_home.php" class="group flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                    <div class="p-1.5 rounded-full bg-gray-100 group-hover:bg-blue-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="font-medium">Back to Dashboard</span>
                </a>
                <div class="hidden md:block text-sm text-gray-600">
                    Student Leaderboards
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                    Student Leaderboards
                </h1>
                <p class="text-gray-600 mt-1">Recognizing our most active students in the lab</p>
            </div>
            
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <div class="relative inline-block text-left">
                    <button id="exportBtn" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-download mr-2 text-gray-500"></i>
                        Export
                    </button>
                    <div id="exportMenu" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <i class="fas fa-file-csv mr-2 text-green-500"></i> Export as CSV
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
                
                <button id="refreshBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                    Refresh Data
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Total Sessions Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-laptop-code text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Sit-in Sessions</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo number_format($stats['total_sessions']); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Unique Students Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Students</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo number_format($stats['unique_students']); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Hours Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Lab Hours</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo number_format($totalHours); ?> hours
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Students Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 sm:px-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-white">
                        Top 5 Students by Sessions
                    </h3>
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Most Active
                    </span>
                </div>
            </div>
            
            <?php if (empty($topStudents)): ?>
                <div class="px-4 py-16 sm:px-6 text-center">
                    <div class="flex flex-col items-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                            <i class="fas fa-user-graduate text-blue-400 text-2xl"></i>
                        </div>
                        <h3 class="mt-3 text-lg font-medium text-gray-900">No data available</h3>
                        <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                            No sit-in sessions have been recorded yet. Data will appear here once students start using the lab.
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <div class="lg:flex">
                    <!-- Leaderboard Cards -->
                    <div class="lg:w-3/5">
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($topStudents as $index => $student): ?>
                                <?php 
                                    // Define medal colors for top 3
                                    $medalClass = '';
                                    $medalIcon = '';
                                    
                                    if ($index === 0) {
                                        $medalClass = 'text-yellow-500';
                                        $medalIcon = '<i class="fas fa-medal text-yellow-500 mr-2"></i>';
                                    } elseif ($index === 1) {
                                        $medalClass = 'text-gray-400';
                                        $medalIcon = '<i class="fas fa-medal text-gray-400 mr-2"></i>';
                                    } elseif ($index === 2) {
                                        $medalClass = 'text-yellow-700';
                                        $medalIcon = '<i class="fas fa-medal text-yellow-700 mr-2"></i>';
                                    }
                                    
                                    // Calculate hours from minutes
                                    $hours = round($student['total_minutes'] / 60, 1);
                                ?>
                                <li class="px-4 py-5 sm:px-6 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="relative">
                                                <img class="h-16 w-16 rounded-full object-cover border-2 <?php echo $index === 0 ? 'border-yellow-400' : 'border-gray-200'; ?>" 
                                                        src="../<?php echo !empty($student['profileImg']) ? $student['profileImg'] : 'images/person.jpg'; ?>" 
                                                        alt="<?php echo htmlspecialchars($student['name']); ?>">
                                                    <span class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-<?php echo $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'yellow'); ?>-100 border border-white flex items-center justify-center text-sm font-bold <?php echo $medalClass; ?>">
                                                        <?php echo $index + 1; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900 flex items-center">
                                                    <?php echo $medalIcon; ?>
                                                    <?php echo htmlspecialchars($student['name']); ?>
                                                </h4>
                                                <div class="mt-1 flex items-center">
                                                    <span class="text-sm text-gray-500 mr-3">
                                                        <i class="fas fa-graduation-cap mr-1 text-blue-500"></i>
                                                        <?php echo htmlspecialchars($student['course'] . ' - ' . $student['level']); ?>
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                                        <?php echo htmlspecialchars($student['email']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <div class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                                <?php echo number_format($student['session_count']); ?> sessions
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                <?php echo number_format($hours); ?> hours in lab
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- Chart Section -->
                    <div class="lg:w-2/5 p-6 border-l border-gray-200">
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Attendance Distribution</h4>
                            <p class="text-sm text-gray-500">Visual comparison of top 5 students' lab attendance patterns</p>
                        </div>
                        <div class="h-64">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle export menu
            const exportBtn = document.getElementById('exportBtn');
            const exportMenu = document.getElementById('exportMenu');
            
            if (exportBtn && exportMenu) {
                exportBtn.addEventListener('click', function() {
                    exportMenu.classList.toggle('hidden');
                });
                
                // Close export menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!exportBtn.contains(event.target) && !exportMenu.contains(event.target)) {
                        exportMenu.classList.add('hidden');
                    }
                });
            }
            
            // Refresh button animation
            const refreshBtn = document.getElementById('refreshBtn');
            
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    icon.classList.add('animate-spin');
                    
                    // Simulate refresh
                    setTimeout(function() {
                        icon.classList.remove('animate-spin');
                        location.reload();
                    }, 1000);
                });
            }
            
            <?php if (!empty($topStudents)): ?>
            // Attendance chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php foreach ($topStudents as $student): ?>
                            '<?php echo htmlspecialchars($student['name']); ?>',
                        <?php endforeach; ?>
                    ],
                    datasets: [{
                        label: 'Number of Sessions',
                        data: [
                            <?php foreach ($topStudents as $student): ?>
                                <?php echo $student['session_count']; ?>,
                            <?php endforeach; ?>
                        ],
                        backgroundColor: [
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(201, 203, 207, 0.8)',
                            'rgba(205, 127, 50, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ],
                        borderColor: [
                            'rgb(255, 205, 86)',
                            'rgb(201, 203, 207)',
                            'rgb(205, 127, 50)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            <?php endif; ?>
        });
    </script>
</body>
</html>