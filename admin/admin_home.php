<?php 
    include("navbar_admin.php"); 

    if (!isset($_SESSION['admin'])) {
        header("Location: admin_login.php");
        exit();
    }

    $showAlert = false;
    if (isset($_SESSION['login_success'])) {
        $showAlert = true;
        unset($_SESSION['login_success']); 
    }

    include('./conn_back/postannounce.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <div class="mt-3 md:mt-0">
                <p class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></p>
            </div>
        </div>
        
        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Students Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-bold text-gray-800">214</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-user-graduate text-blue-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>12%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">Since last month</span>
                </div>
            </div>
            
            <!-- Currently Sit-in Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Currently Sit-in</p>
                        <p class="text-2xl font-bold text-gray-800">45</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-chair text-green-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>8%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">From yesterday</span>
                </div>
            </div>
            
            <!-- Total Sit-in Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Sit-in</p>
                        <p class="text-2xl font-bold text-gray-800">3,450</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-history text-yellow-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>23%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">This semester</span>
                </div>
            </div>
            
            <!-- Sessions Today Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sessions Today</p>
                        <p class="text-2xl font-bold text-gray-800">142</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-calendar-day text-purple-500"></i>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-red-500 text-sm font-medium">
                        <i class="fas fa-arrow-down mr-1"></i>5%
                    </span>
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
                            <button class="px-3 py-1 text-sm rounded-md bg-blue-50 text-blue-600 font-medium">Weekly</button>
                            <button class="px-3 py-1 text-sm rounded-md text-gray-500 hover:bg-gray-100">Monthly</button>
                            <button class="px-3 py-1 text-sm rounded-md text-gray-500 hover:bg-gray-100">Yearly</button>
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
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-xs text-gray-600">CCS Students</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-xs text-gray-600">COE Students</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                <span class="text-xs text-gray-600">COED Students</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-xs text-gray-600">Other Colleges</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Session Usage -->
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 text-lg mb-4">Session Usage</h2>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Morning</span>
                                    <span class="text-gray-600">78%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Afternoon</span>
                                    <span class="text-gray-600">95%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 95%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Evening</span>
                                    <span class="text-gray-600">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Weekend</span>
                                    <span class="text-gray-600">25%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 25%"></div>
                                </div>
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
                            <div class="max-h-96 overflow-y-auto custom-scrollbar pr-1" id="announcementScroll">
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
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['CCS', 'COE', 'COED', 'Others'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: ['#3b82f6', '#ef4444', '#f59e0b', '#10b981'],
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
                        }
                    },
                    cutout: '70%'
                }
            });
        }

        // Student Year Level Chart
        const studentYearLevelCtx = document.getElementById('studentYearLevelChart');
        if (studentYearLevelCtx) {
            new Chart(studentYearLevelCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Freshmen', 'Sophomore', 'Junior', 'Senior'],
                    datasets: [{
                        label: 'Students',
                        data: [65, 84, 74, 43],
                        backgroundColor: [
                            'rgba(79, 70, 229, 0.7)',
                            'rgba(79, 70, 229, 0.5)',
                            'rgba(79, 70, 229, 0.3)',
                            'rgba(79, 70, 229, 0.2)'
                        ],
                        borderWidth: 0,
                        borderRadius: 4,
                        barThickness: 24
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
                            grid: {
                                display: true,
                                drawBorder: false
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
    </style>
</body>
</html>