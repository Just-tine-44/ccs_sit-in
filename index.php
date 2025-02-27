<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tailwind.min.css">
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <title>CCS | Home</title>
</head>
<body>
<nav class="bg-white shadow mb-8 font-sans">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <p class="text-gray-800 font-bold text-xl">College of Computer Studies Sit-in Monitoring System</p>
            <div class="flex space-x-4 items-center">
                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button class="text-gray-800 hover:text-blue-500 relative">
                       <a href="#">Notifications <span class="ml-1 text-lg">&#9662;</span></a> 
                    </button>
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-md">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">No new notifications</a>
                    </div>
                </div>
                
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">Home</a>
                <a href="login.php" class="text-gray-800 hover:text-blue-500 relative">Login</a>
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">History</a>
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">Reservation</a>
            </div>
        </div>
    </div>
</nav>


<div class="flex items-center justify-center p-12 space-x-8">
    <!-- UC Logo Card -->
    <div class="w-[320px] h-[400px] hover:shadow-2xl hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
        <div class="flip-card-inner w-full h-full">
            <div class="flip-card-front bg-white border-2 border-gray-300 rounded-lg shadow-lg p-6 flex items-center justify-center w-full h-full">
                <img src="images/logouc.jpg" alt="UC Logo" class="w-[200px] h-[200px] object-contain">
            </div>
            <div class="flip-card-back bg-blue-100 border-2 border-blue-300 rounded-lg shadow-lg p-6 flex items-center justify-center w-full h-full">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-blue-800 mb-2">University of Cebu</h3>
                    <p class="text-blue-600 text-lg">Founded in 1964</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CCS Logo Card -->
    <div class="w-[320px] h-[400px] hover:shadow-2xl hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
        <div class="flip-card-inner w-full h-full">
            <div class="flip-card-front bg-white border-2 border-gray-300 rounded-lg shadow-lg p-6 flex items-center justify-center w-full h-full">
                <img src="images/logoccs.png" alt="CCS Logo" class="w-[200px] h-[200px] object-contain">
            </div>
            <div class="flip-card-back bg-purple-100 border-2 border-purple-300 rounded-lg shadow-lg p-6 flex items-center justify-center w-full h-full">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-purple-800 mb-2">College of Computer Studies</h3>
                    <p class="text-purple-600 text-lg">Established in 1983</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Man PC Card -->
    <div class="w-[320px] h-[400px] hover:shadow-2xl hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
        <div class="flip-card-inner w-full h-full">
            <div class="flip-card-front bg-white border-2 border-gray-300 rounded-lg shadow-lg p-6 flex items-center justify-center w-full h-full">
                <img src="images/manpc.png" alt="Man PC" class="w-[200px] h-[200px] object-contain">
            </div>
            <div class="flip-card-back bg-green-100 border-2 border-green-300 rounded-lg shadow-lg p-6 flex items-center justify-center w-full h-full">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-green-800 mb-2">Computer Laboratory</h3>
                    <p class="text-green-600 text-lg">Modern facilities for students</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('a[href="#"]').forEach(function(link) {
            link.addEventListener("click", function(event) {
                event.preventDefault(); // Prevent default action

                Swal.fire({
                    title: "Access Restricted!",
                    text: "This feature requires an account. Please log in or sign up.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Go to Login",
                    cancelButtonText: "Cancel",
                    allowOutsideClick: false,
                    customClass: {
                        popup: "animate__animated animate__fadeInUp animate__faster" 
                    },
                    didOpen: () => {
                        document.querySelector('.swal2-popup').classList.add('animate__fadeInUp');
                    },
                    willClose: () => {
                        document.querySelector('.swal2-popup').classList.add('animate__fadeOutDown');
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login.php"; 
                    }
                });
            });
        });
    });
</script>

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