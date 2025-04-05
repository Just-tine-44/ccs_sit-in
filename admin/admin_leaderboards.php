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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 8px 10px -6px rgba(59, 130, 246, 0.1);
        }
        .podium-1 { border-color: #FFD700; }
        .podium-2 { border-color: #C0C0C0; }
        .podium-3 { border-color: #CD7F32; }
        .medal-1 { color: #FFD700; }
        .medal-2 { color: #C0C0C0; }
        .medal-3 { color: #CD7F32; }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .pulse-animation {
            animation: pulse 2s infinite ease-in-out;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation header with soft shadow and subtle gradient -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="admin_home.php" class="group flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                    <div class="p-1.5 rounded-full bg-blue-50 group-hover:bg-blue-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="font-medium">Back to Dashboard</span>
                </a>
                <div class="hidden md:flex items-center space-x-2">
                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-trophy text-yellow-600 text-sm"></i>
                    </div>
                    <span class="font-medium text-gray-700">Student Leaderboards</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Header with modern styling -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 animate-fade-in">
            <div>
                <div class="inline-flex items-center bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-4 py-1 rounded-full text-sm mb-3">
                    <i class="fas fa-star mr-2"></i>
                    Performance Metrics
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                    Student Leaderboards
                </h1>
                <p class="text-gray-600 mt-2 max-w-2xl">
                    Recognizing our most dedicated students based on lab attendance and engagement metrics.
                </p>
            </div>
            
            <div class="mt-6 md:mt-0 flex items-center space-x-3">
                <div class="relative inline-block text-left">
                    <button id="exportBtn" type="button" class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-download mr-2 text-blue-500"></i>
                        Export Data
                    </button>
                    <div id="exportMenu" class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 hidden z-10 divide-y divide-gray-100">
                        <div class="py-3 px-4">
                            <p class="text-sm font-semibold text-gray-900">Export Options</p>
                            <p class="text-xs text-gray-500 mt-1">Download leaderboard data</p>
                        </div>
                        <div class="py-1">
                            <a href="#" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-file-csv text-green-500 mr-3 text-lg"></i>
                                Export as CSV
                            </a>
                            <a href="#" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-file-pdf text-red-500 mr-3 text-lg"></i>
                                Export as PDF
                            </a>
                            <a href="#" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-file-excel text-emerald-500 mr-3 text-lg"></i>
                                Export as Excel
                            </a>
                        </div>
                    </div>
                </div>
                
                <button id="refreshBtn" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh Data
                </button>
            </div>
        </div>
        
        <!-- Stats Cards with elegant hover effects -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <!-- Total Sessions Card -->
            <div class="stats-card bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-300 border border-gray-100">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <i class="fas fa-laptop-code text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Sit-in Sessions</dt>
                                <dd>
                                    <div class="text-2xl font-bold text-gray-900">
                                        <?php echo number_format($stats['total_sessions']); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <div class="text-xs text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-chart-line text-blue-500 mr-1"></i>
                                Total completed lab sessions
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Unique Students Card -->
            <div class="stats-card bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-300 border border-gray-100">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Students</dt>
                                <dd>
                                    <div class="text-2xl font-bold text-gray-900">
                                        <?php echo number_format($stats['unique_students']); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <div class="text-xs text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-user-check text-purple-500 mr-1"></i>
                                Unique students using the lab
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Hours Card -->
            <div class="stats-card bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-300 border border-gray-100">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Lab Hours</dt>
                                <dd>
                                    <div class="text-2xl font-bold text-gray-900">
                                        <?php echo number_format($totalHours); ?> hours
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <div class="text-xs text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-calendar-check text-green-500 mr-1"></i>
                                Cumulative time spent in lab
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Leaderboard Section -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-lg overflow-hidden rounded-xl border border-gray-200 animate-fade-in">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-yellow-500 to-amber-600 sm:px-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-white flex items-center">
                                <i class="fas fa-trophy mr-2"></i>
                                Top 5 Students by Lab Attendance
                            </h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-yellow-600">
                                <i class="fas fa-medal mr-1"></i> Hall of Fame
                            </span>
                        </div>
                    </div>
                    
                    <?php if (empty($topStudents)): ?>
                        <div class="px-4 py-16 sm:px-6 text-center">
                            <div class="flex flex-col items-center">
                                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100">
                                    <i class="fas fa-user-graduate text-yellow-400 text-3xl"></i>
                                </div>
                                <h3 class="mt-5 text-xl font-medium text-gray-900">No data available yet</h3>
                                <p class="mt-3 text-sm text-gray-500 max-w-md mx-auto">
                                    No sit-in sessions have been recorded yet. Data will appear here once students start using the lab.
                                </p>
                                <div class="mt-6">
                                    <button id="checkLaterBtn" type="button" class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                                        <i class="fas fa-bell mr-2"></i>
                                        Check Again Later
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($topStudents as $index => $student): ?>
                                <?php 
                                    // Define medal colors for top 3
                                    $medalClass = '';
                                    $medalIcon = '';
                                    $podiumClass = '';
                                    $bgColor = 'bg-white';
                                    $badgeColor = 'bg-blue-100 text-blue-800';
                                    
                                    if ($index === 0) {
                                        $medalClass = 'medal-1';
                                        $medalIcon = '<i class="fas fa-medal text-yellow-500 mr-2 text-lg"></i>';
                                        $podiumClass = 'podium-1';
                                        $bgColor = 'bg-gradient-to-r from-yellow-50 to-white';
                                        $badgeColor = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($index === 1) {
                                        $medalClass = 'medal-2';
                                        $medalIcon = '<i class="fas fa-medal text-gray-400 mr-2 text-lg"></i>';
                                        $podiumClass = 'podium-2';
                                        $bgColor = 'bg-gradient-to-r from-gray-50 to-white';
                                        $badgeColor = 'bg-gray-100 text-gray-800';
                                    } elseif ($index === 2) {
                                        $medalClass = 'medal-3';
                                        $medalIcon = '<i class="fas fa-medal text-yellow-700 mr-2 text-lg"></i>';
                                        $podiumClass = 'podium-3';
                                        $bgColor = 'bg-gradient-to-r from-amber-50 to-white';
                                        $badgeColor = 'bg-amber-100 text-amber-800';
                                    }
                                    
                                    // Calculate hours from minutes
                                    $hours = round($student['total_minutes'] / 60, 1);
                                ?>
                                <li class="px-6 py-6 <?php echo $bgColor; ?> hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="relative">
                                                    <img class="h-16 w-16 rounded-full object-cover border-3 shadow-md <?php echo $podiumClass; ?>" 
                                                         src="../<?php echo !empty($student['profileImg']) ? $student['profileImg'] : 'images/person.jpg'; ?>" 
                                                         alt="<?php echo htmlspecialchars($student['name']); ?>">
                                                    <span class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-white border-2 <?php echo $podiumClass; ?> flex items-center justify-center text-sm font-bold <?php echo $medalClass; ?> shadow-sm">
                                                        <?php echo $index + 1; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                                    <?php echo $medalIcon; ?>
                                                    <?php echo htmlspecialchars($student['name']); ?>
                                                    <?php if ($index === 0): ?>
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-crown mr-1"></i> Top Student
                                                        </span>
                                                    <?php endif; ?>
                                                </h4>
                                                <div class="mt-1 flex flex-col sm:flex-row sm:items-center">
                                                    <span class="text-sm text-gray-500 flex items-center mr-3 mb-1 sm:mb-0">
                                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100 text-blue-500 mr-1">
                                                            <i class="fas fa-graduation-cap text-xs"></i>
                                                        </span>
                                                        <?php echo htmlspecialchars($student['course'] . ' - ' . $student['level']); ?>
                                                    </span>
                                                    <span class="text-sm text-gray-500 flex items-center">
                                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100 text-blue-500 mr-1">
                                                            <i class="fas fa-envelope text-xs"></i>
                                                        </span>
                                                        <?php echo htmlspecialchars($student['email']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <div class="px-3 py-1 rounded-full <?php echo $badgeColor; ?> text-sm font-medium flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                <?php echo number_format($student['session_count']); ?> sessions
                                            </div>
                                            <div class="mt-2 text-sm text-gray-500 flex items-center">
                                                <i class="far fa-clock mr-1"></i>
                                                <?php echo number_format($hours); ?> hours in lab
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Chart Section -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-lg overflow-hidden rounded-xl border border-gray-200 animate-fade-in h-full">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 sm:px-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-white flex items-center">
                                <i class="fas fa-chart-column mr-2"></i>
                                Attendance Metrics
                            </h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-blue-600">
                                <i class="fas fa-chart-bar mr-1"></i> Analytics
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <?php if (empty($topStudents)): ?>
                            <div class="text-center py-8">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                                    <i class="fas fa-chart-bar text-blue-400 text-2xl"></i>
                                </div>
                                <h3 class="mt-3 text-lg font-medium text-gray-900">No data to visualize</h3>
                                <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                                    Charts will appear here once student attendance data is available.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Attendance Distribution</h4>
                                <p class="text-sm text-gray-500">Sessions completed by top students</p>
                            </div>
                            <div class="chart-container">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
            
            // Check later button animation
            const checkLaterBtn = document.getElementById('checkLaterBtn');
            
            if (checkLaterBtn) {
                checkLaterBtn.addEventListener('click', function() {
                    this.innerHTML = '<i class="fas fa-check-circle mr-2"></i> We\'ll notify you';
                    this.classList.add('bg-green-500');
                    
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
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
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverOffset: 4
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
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#111827',
                            bodyColor: '#4B5563',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12,
                            boxWidth: 10,
                            usePointStyle: true,
                            borderColor: 'rgba(220, 220, 220, 1)',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                title: function(tooltipItem) {
                                    return tooltipItem[0].label;
                                },
                                label: function(context) {
                                    return ' ' + context.parsed.y + ' sessions completed';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                },
                                color: '#6B7280'
                            },
                            grid: {
                                display: true,
                                color: 'rgba(243, 244, 246, 1)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6B7280'
                            },
                            grid: {
                                display: false
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