<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tailwind.min.css">
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <title>CCS</title>
</head>
<body>
<nav class="bg-white shadow mb-8 font-sans">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="#" class="text-gray-800 font-bold text-xl hover:text-blue-500 relative">College of Computer Studies Sit-in Monitoring System</a>
            <div class="flex space-x-4 items-center">
                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()" class="text-gray-800 hover:text-blue-500 relative">
                        Notifications <span class="ml-1 text-lg">&#9662;</span>
                    </button>
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-md">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">No new notifications</a>
                    </div>
                </div>
                
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">Home</a>
                <a href="login.php" class="text-gray-800 hover:text-blue-500 relative">Login</a>
                <a href="#" onclick="showForm('register-form')" class="text-gray-800 hover:text-blue-500 relative">Register</a>
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">History</a>
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">Reservation</a>
            </div>
        </div>
    </div>
</nav>

    <div class="flex justify-center space-x-4">
        <img src="images/ccslogo.png" alt="CCS Logo" class="h-48 w-auto">
        <img src="images/uclogo.jpg" alt="UC Logo" class="h-48 w-auto">
    </div>
    <script src="script.js"></script>
</body>
</html>

<style>
    a.relative {
        position: relative;
    }

    a.relative::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: blue; /* Customize the color of the line */
        transition: width 0.3s ease;
    }

    a.relative:hover::after {
        width: 100%;
    }
</style> 