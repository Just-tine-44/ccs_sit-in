<?php 
    include("navbar_admin.php"); 
    session_start();
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
<body class="bg-gray-100 p-0 m-0">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-5xl mx-auto">
        <!-- Statistics Card -->
        <div class="bg-white shadow-md rounded-lg h-1/2">
            <div class="bg-blue-500 text-white px-4 py-2 rounded-t-lg font-semibold">
                <i class="fas fa-chart-bar"></i> Statistics
            </div>
            <div class="p-4">
                <p class="text-xs font-semibold">Students Registered:</p>
                <p class="text-xs font-semibold">Currently Sit-in:</p>
                <p class="text-xs font-semibold">Total Sit-in:</p>
                <div class="flex flex-col">
                    <canvas id="pieChart" class="mt-2 max-h-40"></canvas> <!-- Increased the height of the pie chart -->
                </div>
            </div>
            
            <div class="my-4"></div>
            
            <!-- Student Year Level Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden md:col-span-2">
                <div class="bg-blue-500 text-white px-4 py-2 rounded-t-lg font-semibold">
                    <i class="fas fa-graduation-cap mr-2"></i>Students Year Level Distribution
                </div>
                <div class="p-4">
                    <canvas id="studentYearLevelChart" class="max-h-52"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Announcements Card -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-blue-500 text-white px-4 py-2 rounded-t-lg font-semibold">
                <i class="fas fa-bullhorn"></i> Announcement
            </div>
            <div class="p-4">
                <form action="admin_home.php" method="POST">
                    <textarea name="message" class="w-full border rounded-md p-2 text-sm mt-2 resize-none" placeholder="New Announcement..." rows="4" cols="50" required></textarea>
                    <input type="hidden" name="new_announcement" value="1">
                    <div class="flex justify-end mt-2">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
                <h3 class="mt-3 font-semibold text-gray-700 text-sm">Posted Announcements</h3>
                <?php if (empty($announcements)): ?>
                    <p class="text-gray-500 mt-3">No announcements available at the moment. Please post an announcement.</p>
                <?php else: ?>
                    <div class="max-h-80 overflow-y-auto custom-scrollbar" id="announcementScroll">
                            <?php foreach ($announcements as $announcement): 
                                // Properly escape messages for both HTML and JavaScript
                                $safe_id = htmlspecialchars($announcement['announcement_id'] ?? '');
                                $safe_message = htmlspecialchars($announcement['message'] ?? '', ENT_QUOTES);
                                $safe_js_message = addslashes(htmlspecialchars($announcement['message'] ?? '', ENT_QUOTES));
                            ?>
                            <div class="mt-2 border-t pt-1 text-sm">
                                <p class="text-gray-600 font-semibold mt-2">
                                    <?php echo htmlspecialchars($announcement['admin_name'] ?? ''); ?> | 
                                    <?php echo date('Y-M-d h:i A', strtotime($announcement['post_date'] ?? '')); ?>
                                </p>
                                <p class="text-gray-700 text-justify mb-4 mr-3">
                                    <?php echo $safe_message; ?>
                                </p>
                                <div class="flex justify-end space-x-2 mr-2 mb-3">
                                    <button 
                                        class="bg-yellow-500 text-white px-2 py-1 rounded-md text-sm hover:bg-yellow-600" 
                                        onclick="editAnnouncement('<?php echo $safe_id; ?>', '<?php echo $safe_js_message; ?>')">
                                        Edit
                                    </button>
                                    <button 
                                        class="bg-red-500 text-white px-2 py-1 rounded-md text-sm hover:bg-red-600" 
                                        onclick="deleteAnnouncement('<?php echo $safe_id; ?>')">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-4 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-semibold mb-2">Edit Announcement</h2>
            <form id="editForm" method="POST" action="admin_home.php">
                <textarea id="editMessage" name="edit_message" class="w-full border rounded-md p-2 text-sm mt-2 resize-none" rows="4" required></textarea>
                <input type="hidden" id="editId" name="edit_id">
                <div class="flex justify-end mt-2">
                    <button type="button" class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm hover:bg-gray-600 mr-2" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600">Save</button>
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
                    text: 'Welcome, Admin-CCS!',
                    showConfirmButton: false,
                    timer: 2500
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
        const ctx = document.getElementById('pieChart');
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Waiting', 'Waiting', 'Waiting', 'Waiting', 'Waiting'],
                    datasets: [{
                        data: [35, 45, 25, 10, 15],
                        backgroundColor: ['#3b82f6', '#ef4444', '#f59e0b', '#fbbf24', '#10b981']
                    }]
                }
            });
        }

        const studentYearLevelCtx = document.getElementById('studentYearLevelChart');
        if (studentYearLevelCtx) {
            new Chart(studentYearLevelCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Freshman', 'Sophomore', 'Junior', 'Senior'],
                    datasets: [{
                        label: 'Students Year Level',
                        data: [15, 20, 125, 10],
                        backgroundColor: ['#ff9999', '#ffcc99', '#ccccff', '#99ccff']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Students'
                            }
                        }
                    },
                }
            });
        }

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
    
    // functions for delete announcement
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
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
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
</body>
</html>