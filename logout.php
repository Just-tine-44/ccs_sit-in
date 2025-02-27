<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to log out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php?confirm=true';
                } else {
                    window.location.href = 'homepage.php';
                }
            });
        });
    </script>
</body>
</html>

<?php
if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header("Location: index.php");
    exit();
}
?>