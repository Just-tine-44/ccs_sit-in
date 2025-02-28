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
    <link href="../css/tailwind.min.css" rel="stylesheet"> <!-- Use a local or CDN link for production -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Ensure SweetAlert2 is loaded -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 p-0 m-0">
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
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-5xl mx-auto">
        <!-- Statistics Card -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-t-lg font-semibold">Statistics</div>
            <div class="p-4">
            <p class="text-xs font-semibold">Students Registered:</p>
            <p class="text-xs font-semibold">Currently Sit-in:</p>
            <p class="text-xs font-semibold">Total Sit-in:</p>
            <canvas id="pieChart" class="mt-2"></canvas>
            </div>
        </div>
        
        <!-- Announcements Card -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-t-lg font-semibold">
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
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="mt-1 border-t pt-1 text-sm">
                                <p class="text-gray-600 font-semibold"><?php echo $announcement['admin_name']; ?> | <?php echo date('Y-M-d h:i A', strtotime($announcement['post_date'])); ?></p>
                                <p class="text-gray-700 text-justify mb-4 mr-3"><?php echo $announcement['message']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<script>
    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
        labels: ['Waiting', 'Waiting', 'Waiting', 'Waiting', 'Waiting'],
        datasets: [{
            data: [35, 45, 25, 10, 15],
            backgroundColor: ['#3b82f6', '#ef4444', '#f59e0b', '#fbbf24', '#10b981']
        }]
        }
    });
</script>
</body>
</html>