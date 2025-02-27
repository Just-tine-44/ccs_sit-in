<?php 
    include("navbar_admin.php"); 
?>

<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$showAlert = false;
if (isset($_SESSION['login_success'])) {
    $showAlert = true;
    unset($_SESSION['login_success']); // Remove session flag after showing alert
}
?>

<?php if ($showAlert): ?> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            toast: true, 
            position: 'top-start', 
            icon: 'success',
            title: 'Login Successful',
            text: 'Welcome, Admin-CCS!',
            showConfirmButton: false,
            timer: 2500
        });
    </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/logoccs.png">
    <title>Admin Home</title>
</head>
<body>
    <div class="h-screen flex items-center justify-center bg-white py-4 overflow-hidden">
        <div class="bg-gray-200 p-8 rounded-lg shadow-lg text-center mt-[70px]"> <!-- Adjusted here -->
            <h1 class="text-2xl font-bold mb-4">Hello, Welcome to admin panel</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-2">Reports</h2>
                    <p>View and manage reports.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-2">Analysis</h2>
                    <p>Analyze data and trends.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-2">Reports</h2>
                    <p>Generate new reports.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
