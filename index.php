<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tailwind.min.css">
    <link rel="icon" type="image/png" href="images/ccswb.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>CCS | Home</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Hero Section with Blurred Glass Navbar -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-800 h-[500px]">
        <!-- Animated Particles Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="particles-container">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
        </div>
        
        <!-- Glass Navbar -->
        <nav class="bg-white/10 backdrop-blur-md border-b border-white/20 sticky top-0 z-50 transition-all duration-300" id="navbar">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center gap-3">
                        <img src="images/ccswb.png" alt="CCS Logo" class="h-10 w-10">
                        <p class="text-white font-semibold text-xl hidden md:block">CCS Sit-in Monitoring System</p>
                        <p class="text-white font-semibold text-xl md:hidden">CCS Monitor</p>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="text-white md:hidden" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6 items-center">
                        <a href="#" class="text-white hover:text-blue-200 transition-colors px-2 py-1 rounded-md flex items-center group">
                            <i class="fas fa-home mr-2"></i>
                            <span>Home</span>
                            <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-blue-200 group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                        </a>
                        <a href="login.php" class="text-white hover:text-blue-200 transition-colors px-2 py-1 rounded-md flex items-center group">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span>Login</span>
                            <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-blue-200 group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                        </a>
                        <a href="#" class="text-white hover:text-blue-200 transition-colors px-2 py-1 rounded-md flex items-center group">
                            <i class="fas fa-history mr-2"></i>
                            <span>History</span>
                            <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-blue-200 group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                        </a>
                        <a href="#" class="text-white hover:text-blue-200 transition-colors px-2 py-1 rounded-md flex items-center group">
                            <i class="fas fa-calendar-check mr-2"></i>
                            <span>Reservation</span>
                            <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-blue-200 group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                        </a>
                        
                        <!-- Notifications Dropdown -->
                        <div class="relative group">
                            <button class="text-white hover:text-blue-200 transition-colors px-2 py-1 rounded-md flex items-center">
                                <i class="fas fa-bell mr-2"></i>
                                <span>Notifications</span>
                                <i class="fas fa-chevron-down ml-2 text-xs transition-transform group-hover:rotate-180"></i>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-xl overflow-hidden transition-all duration-300 z-50">
                                <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium">
                                    <div class="flex justify-between items-center">
                                        <span>Notifications</span>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs">0</span>
                                    </div>
                                </div>
                                <div class="p-4 text-center text-gray-500">
                                    <i class="fas fa-bell-slash text-2xl mb-2 text-gray-300"></i>
                                    <p>No new notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden transition-all duration-300 ease-in-out" id="mobile-menu">
                <div class="px-4 py-3 space-y-3 bg-white/10 backdrop-blur-md border-t border-white/20">
                    <a href="#" class="block text-white hover:bg-white/10 rounded-md px-3 py-2 transition-colors">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                    <a href="login.php" class="block text-white hover:bg-white/10 rounded-md px-3 py-2 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="#" class="block text-white hover:bg-white/10 rounded-md px-3 py-2 transition-colors">
                        <i class="fas fa-history mr-2"></i> History
                    </a>
                    <a href="#" class="block text-white hover:bg-white/10 rounded-md px-3 py-2 transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i> Reservation
                    </a>
                    <a href="#" class="block text-white hover:bg-white/10 rounded-md px-3 py-2 transition-colors">
                        <i class="fas fa-bell mr-2"></i> Notifications
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Hero Content -->
        <div class="container mx-auto px-6 py-12 relative z-10">
            <div class="max-w-2xl animate__animated animate__fadeInUp">
                <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                    College of Computer Studies
                </h1>
                <h2 class="text-2xl md:text-3xl font-medium text-blue-100 mt-2">
                    Sit-in Monitoring System
                </h2>
                <p class="text-blue-200 mt-6 text-lg">
                    Track computer laboratory usage and make reservations efficiently in one place.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="login.php" class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Login Now
                    </a>
                    <a href="#about" class="px-6 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-full hover:bg-white/10 transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200">
                <path fill="#ffffff" fill-opacity="1" d="M0,128L48,117.3C96,107,192,85,288,80C384,75,480,85,576,101.3C672,117,768,139,864,133.3C960,128,1056,96,1152,80C1248,64,1344,64,1392,64L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- About Section with Modern Cards -->
    <section id="about" class="py-16 px-6">
        <div class="container mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800">About Our Monitoring System</h2>
                <div class="w-24 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                    Discover the key features and components of our advanced laboratory monitoring system.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1: University -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative">
                    <div class="h-48 overflow-hidden flex items-center justify-center p-4 bg-gray-50">
                        <img src="images/wbuclogo.png" alt="CCS Logo" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-900 via-blue-900/70 to-transparent opacity-0 group-hover:opacity-80 transition-opacity duration-500 flex items-center justify-center">
                        <div class="p-6 text-white transform translate-y-6 group-hover:translate-y-0 transition-transform duration-500 text-center">
                            <h3 class="text-xl font-bold">University of Cebu</h3>
                            <p class="mt-2">Founded in 1964, the University of Cebu has been a center of academic excellence for decades.</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">University of Cebu</h3>
                        <p class="text-gray-600 mt-2">Founded in 1964</p>
                        <div class="w-10 h-1 bg-blue-600 rounded-full mt-4 transform origin-left scale-0 group-hover:scale-100 transition-transform duration-500"></div>
                    </div>
                </div>
                
                <!-- Card 2: College -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative">
                    <div class="h-48 overflow-hidden flex items-center justify-center p-4 bg-gray-50">
                        <img src="images/wbccs.png" alt="CCS Logo" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-purple-900 via-purple-900/70 to-transparent opacity-0 group-hover:opacity-80 transition-opacity duration-500 flex items-center justify-center">
                        <div class="p-6 text-white transform translate-y-6 group-hover:translate-y-0 transition-transform duration-500 text-center">
                            <h3 class="text-xl font-bold">College of Computer Studies</h3>
                            <p class="mt-2">Established in 1983, CCS has been at the forefront of technology education.</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-purple-600 transition-colors">College of Computer Studies</h3>
                        <p class="text-gray-600 mt-2">Established in 1983</p>
                        <div class="w-10 h-1 bg-purple-600 rounded-full mt-4 transform origin-left scale-0 group-hover:scale-100 transition-transform duration-500"></div>
                    </div>
                </div>
                
                <!-- Card 3: Laboratory -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative">
                    <div class="h-48 overflow-hidden flex items-center justify-center p-4 bg-gray-50">
                        <img src="images/manpc.png" alt="CCS Logo" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-green-900/70 to-transparent opacity-0 group-hover:opacity-80 transition-opacity duration-500 flex items-center justify-center">
                        <div class="p-6 text-white transform translate-y-6 group-hover:translate-y-0 transition-transform duration-500 text-center">
                            <h3 class="text-xl font-bold">Computer Laboratory</h3>
                            <p class="mt-2">Our state-of-the-art facilities provide students with hands-on experience and modern equipment.</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-green-600 transition-colors">Computer Laboratory</h3>
                        <p class="text-gray-600 mt-2">Modern facilities for students</p>
                        <div class="w-10 h-1 bg-green-600 rounded-full mt-4 transform origin-left scale-0 group-hover:scale-100 transition-transform duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-16 px-6 bg-gradient-to-b from-gray-50 to-blue-50">
        <div class="container mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800">System Features</h2>
                <div class="w-24 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                    Discover what makes our sit-in monitoring system efficient and user-friendly.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-blue-100 text-blue-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-desktop text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Lab Monitoring</h3>
                    <p class="text-gray-600">Real-time tracking of computer usage and availability status.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-purple-100 text-purple-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Reservations</h3>
                    <p class="text-gray-600">Book computer stations in advance for your study sessions.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-green-100 text-green-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Usage Analytics</h3>
                    <p class="text-gray-600">Track and analyze laboratory usage patterns and trends.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-amber-100 text-amber-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Notifications</h3>
                    <p class="text-gray-600">Get alerts about reservations, lab closures, and availability.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="py-16 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white relative overflow-hidden">
        <div class="container mx-auto relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Get Started?</h2>
                <p class="text-blue-100 text-lg mb-8">
                    Log in to your account or register now to start using the CCS Sit-in Monitoring System.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="login.php" class="px-8 py-4 bg-white text-blue-700 font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="#" class="px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-full hover:bg-white/10 transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-question-circle"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full"></div>
            <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white opacity-10 rounded-full"></div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10 px-6">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="images/ccswb.png" alt="CCS Logo" class="h-10 w-10">
                        <h3 class="text-xl font-bold">CCS Monitoring</h3>
                    </div>
                    <p class="text-gray-400">
                        A modern solution for computer laboratory management and monitoring.
                    </p>
                    <div class="flex gap-4 mt-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs"></i> Home
                            </a>
                        </li>
                        <li>
                            <a href="login.php" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs"></i> Login
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs"></i> History
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs"></i> Reservation
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-blue-400"></i>
                            <span class="text-gray-400">123 University Ave, Cebu City, Philippines</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-envelope mt-1 text-blue-400"></i>
                            <span class="text-gray-400">info@ccs-monitor.edu.ph</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-phone-alt mt-1 text-blue-400"></i>
                            <span class="text-gray-400">+63 32 123 4567</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">
                    &copy; 2025 College of Computer Studies. All rights reserved.
                </p>
                <p class="text-gray-500 text-sm mt-2 md:mt-0">
                    AI-Powered ICT: Building the Future with Responsibility
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Navbar Animation on Scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white/80', 'shadow-md');
                navbar.classList.remove('bg-white/10');
            } else {
                navbar.classList.remove('bg-white/80', 'shadow-md');
                navbar.classList.add('bg-white/10');
            }
        });
        
        // Restricted Access Alert
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('a[href="#"]').forEach(function(link) {
                link.addEventListener("click", function(event) {
                    if (!this.classList.contains('no-alert')) {
                        event.preventDefault();
                        
                        Swal.fire({
                            title: "Access Restricted",
                            text: "This feature requires an account. Please log in or sign up.",
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: "Go to Login",
                            cancelButtonText: "Cancel",
                            confirmButtonColor: "#4F46E5",
                            customClass: {
                                popup: "rounded-xl"
                            },
                            backdrop: `rgba(38, 55, 130, 0.4)`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "login.php";
                            }
                        });
                    }
                });
            });
        });
    </script>
    
    <style>
    /* Animated Underline Effect */
    .group {
        position: relative;
    }
    
    /* Animated Particles in Hero */
    .particles-container {
        position: absolute;
        width: 100%;
        height: 100%;
    }
    
    .particle {
        position: absolute;
        width: 5px;
        height: 5px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        pointer-events: none;
        animation: float 15s infinite linear;
    }
    
    .particle:nth-child(1) {
        top: 20%;
        left: 10%;
        width: 80px;
        height: 80px;
        opacity: 0.05;
        animation-duration: 25s;
    }
    
    .particle:nth-child(2) {
        top: 60%;
        left: 30%;
        width: 60px;
        height: 60px;
        opacity: 0.04;
        animation-duration: 30s;
    }
    
    .particle:nth-child(3) {
        top: 40%;
        left: 70%;
        width: 100px;
        height: 100px;
        opacity: 0.06;
        animation-duration: 22s;
    }
    
    .particle:nth-child(4) {
        top: 80%;
        left: 50%;
        width: 50px;
        height: 50px;
        opacity: 0.05;
        animation-duration: 28s;
    }
    
    .particle:nth-child(5) {
        top: 10%;
        left: 80%;
        width: 70px;
        height: 70px;
        opacity: 0.04;
        animation-duration: 20s;
    }
    
    .particle:nth-child(6) {
        top: 30%;
        left: 20%;
        width: 40px;
        height: 40px;
        opacity: 0.06;
        animation-duration: 18s;
    }
    
    .particle:nth-child(7) {
        top: 70%;
        left: 85%;
        width: 90px;
        height: 90px;
        opacity: 0.05;
        animation-duration: 24s;
    }
    
    .particle:nth-child(8) {
        top: 50%;
        left: 40%;
        width: 55px;
        height: 55px;
        opacity: 0.04;
        animation-duration: 26s;
    }
    
    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
        100% {
            transform: translateY(0) rotate(360deg);
        }
    }
    </style>
</body>
</html>