<?php
include('conn/dbcon.php'); // Adjust the path as necessary

// Fetch announcements from the database
$announcements = [];
$result = $conn->query("SELECT * FROM announcements ORDER BY post_date DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>

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
                <!-- Particle 1 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 20%; left: 10%; width: 80px; height: 80px; opacity: 0.10; animation-duration: 25s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 2 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 60%; left: 30%; width: 60px; height: 60px; opacity: 0.10; animation-duration: 30s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 3 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 40%; left: 70%; width: 100px; height: 100px; opacity: 0.10; animation-duration: 22s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 4 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 80%; left: 50%; width: 50px; height: 50px; opacity: 0.10; animation-duration: 28s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 5 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 10%; left: 80%; width: 70px; height: 70px; opacity: 0.10; animation-duration: 20s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 6 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 30%; left: 20%; width: 40px; height: 40px; opacity: 0.10; animation-duration: 18s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 7 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 70%; left: 85%; width: 90px; height: 90px; opacity: 0.10; animation-duration: 24s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 8 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 50%; left: 40%; width: 55px; height: 55px; opacity: 0.10; animation-duration: 26s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 9 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 15%; left: 60%; width: 45px; height: 45px; opacity: 0.10; animation-duration: 19s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 10 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 85%; left: 15%; width: 65px; height: 65px; opacity: 0.10; animation-duration: 27s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 11 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 25%; left: 45%; width: 35px; height: 35px; opacity: 0.10; animation-duration: 21s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 12 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 75%; left: 75%; width: 75px; height: 75px; opacity: 0.10; animation-duration: 23s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 13 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 45%; left: 5%; width: 25px; height: 25px; opacity: 0.10; animation-duration: 17s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 14 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 5%; left: 35%; width: 30px; height: 30px; opacity: 0.10; animation-duration: 29s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 15 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 55%; left: 90%; width: 85px; height: 85px; opacity: 0.10; animation-duration: 26s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
                
                <!-- Particle 16 -->
                <div class="particle absolute rounded-full bg-white" 
                    style="top: 35%; left: 25%; width: 50px; height: 50px; opacity: 0.10; animation-duration: 22s; box-shadow: 0 0 15px 2px rgba(255, 255, 255, 0.3);"></div>
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
                        <a href="login.php?form=register" class="text-white hover:text-blue-200 transition-colors px-2 py-1 rounded-md flex items-center group">
                            <i class="fas fa-user-plus mr-2"></i>
                            <span>Register</span>
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
                    <a href="login.php?form=register" class="block text-white hover:bg-white/10 rounded-md px-3 py-2 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Register
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
                    <a href="login.php?form=register" class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Register Now
                    </a>
                    <a href="#about" class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
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

    <section class="py-16 px-6 bg-white">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Latest Announcements</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                Stay updated with the latest news and information from the Computer Laboratory
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-4 px-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 rounded-full p-1.5">
                            <i class="fas fa-bullhorn text-white"></i>
                        </div>
                        <h3 class="text-white font-semibold">Official Announcements</h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <img src="images/ccswb.png" alt="CCS Logo" class="h-8 w-8 rounded-full bg-white p-0.5">
                    </div>
                </div>
            </div>
            
            <?php if (count($announcements) > 0): ?>
                <div class="h-96 overflow-y-auto custom-scrollbar p-1">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="p-5 border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg text-gray-800 mb-2">
                                        <?php echo htmlspecialchars($announcement['admin_name']); ?>
                                    </h4>
                                    <p class="text-gray-600 mb-3">
                                        <?php echo nl2br(htmlspecialchars($announcement['message'])); ?>
                                    </p>
                                    <?php if (!empty($announcement['image'])): ?>
                                        <div class="mt-3 mb-3">
                                            <img src="<?php echo htmlspecialchars($announcement['image']); ?>" 
                                                alt="Announcement image" 
                                                class="rounded-lg max-h-48 object-cover">
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-center text-sm text-gray-500 mt-2">
                                        <i class="far fa-clock mr-2"></i>
                                        <span>
                                            <?php 
                                                $date = new DateTime($announcement['post_date']);
                                                echo $date->format('F j, Y - g:i A'); 
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        <?php echo htmlspecialchars($announcement['category'] ?? 'General'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="py-16 flex flex-col items-center justify-center text-gray-500">
                    <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                    <p class="text-xl font-medium">No announcements yet</p>
                    <p class="mt-2">Check back later for updates</p>
                </div>
            <?php endif; ?>
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
        
        <!-- Decorative Elements with Animation -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <!-- Background particles with movement -->
            <div class="cta-particle absolute -top-20 -left-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            <div class="cta-particle absolute bottom-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full"></div>
            <div class="cta-particle absolute top-1/2 left-1/4 w-32 h-32 bg-white opacity-10 rounded-full"></div>
            
            <!-- Additional smaller particles -->
            <div class="cta-particle absolute top-1/4 right-1/4 w-16 h-16 bg-white opacity-10 rounded-full"></div>
            <div class="cta-particle absolute bottom-1/4 left-1/3 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <div class="cta-particle absolute top-3/4 right-1/5 w-20 h-20 bg-white opacity-10 rounded-full"></div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-100 to-blue-50 py-12 px-6">
        <div class="container mx-auto">
            <!-- Footer Top Section with Logo and Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Brand Section -->
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <img src="images/ccswb.png" alt="CCS Logo" class="h-10 w-10 transform hover:rotate-12 transition-transform duration-300">
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">CCS Monitoring</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        A modern solution for computer laboratory management and monitoring with advanced tracking features.
                    </p>
                    <div class="flex gap-4 mt-6">
                        <a href="#" class="h-9 w-9 bg-white shadow-sm rounded-full flex items-center justify-center group hover:bg-blue-500 transition-colors duration-300">
                            <i class="fab fa-facebook-f text-gray-500 group-hover:text-white transition-colors duration-300"></i>
                        </a>
                        <a href="#" class="h-9 w-9 bg-white shadow-sm rounded-full flex items-center justify-center group hover:bg-blue-400 transition-colors duration-300">
                            <i class="fab fa-twitter text-gray-500 group-hover:text-white transition-colors duration-300"></i>
                        </a>
                        <a href="#" class="h-9 w-9 bg-white shadow-sm rounded-full flex items-center justify-center group hover:bg-gradient-to-tr from-purple-600 to-orange-500 transition-colors duration-300">
                            <i class="fab fa-instagram text-gray-500 group-hover:text-white transition-colors duration-300"></i>
                        </a>
                        <a href="#" class="h-9 w-9 bg-white shadow-sm rounded-full flex items-center justify-center group hover:bg-blue-600 transition-colors duration-300">
                            <i class="fab fa-linkedin-in text-gray-500 group-hover:text-white transition-colors duration-300"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-5 text-gray-800 pb-2 border-b border-gray-200">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors flex items-center group">
                                <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-home text-xs text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="login.php" class="text-gray-600 hover:text-blue-600 transition-colors flex items-center group">
                                <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-sign-in-alt text-xs text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span>Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors flex items-center group">
                                <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-history text-xs text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span>History</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors flex items-center group">
                                <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-calendar-check text-xs text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span>Reservation</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Us -->
                <div>
                    <h3 class="text-lg font-semibold mb-5 text-gray-800 pb-2 border-b border-gray-200">Contact Us</h3>
                    <ul class="space-y-4">
                        <li>
                            <a href="#" class="flex group">
                                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-map-marker-alt text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span class="text-gray-600 group-hover:text-blue-600 transition-colors duration-300">123 University Ave,<br>Cebu City, Philippines</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:info@ccs-monitor.edu.ph" class="flex group">
                                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-envelope text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span class="text-gray-600 group-hover:text-blue-600 transition-colors duration-300">info@ccs-monitor.edu.ph</span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+6332123456" class="flex group">
                                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500 transition-colors duration-300">
                                    <i class="fas fa-phone-alt text-blue-500 group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span class="text-gray-600 group-hover:text-blue-600 transition-colors duration-300">+63 32 123 4567</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Bottom with Copyright -->
            <div class="mt-12 pt-6 border-t border-blue-100 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 text-sm">
                    &copy; 2025 College of Computer Studies. All rights reserved.
                </p>
                <div class="flex items-center mt-4 md:mt-0">
                    <span class="h-8 w-8 bg-blue-50 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-shield-alt text-blue-500 text-xs"></i>
                    </span>
                    <p class="text-gray-600 text-sm">
                        AI-Powered ICT: Building the Future with Responsibility
                    </p>
                </div>
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

        // Add random animation delays to particles
        document.addEventListener("DOMContentLoaded", function() {
            const particles = document.querySelectorAll('.particle');
            particles.forEach((particle, index) => {
                particle.style.setProperty('--particle-index', Math.random() * 5);
            });
        });
    </script>
    
    <style>
    .particle {
    pointer-events: none;
    animation: float 15s infinite ease-in-out;
    animation-delay: calc(var(--particle-index, 0) * -2s);
    }

    @keyframes float {
    0% {
        transform: translate(0, 0) rotate(0deg) scale(1);
    }
    25% {
        transform: translate(10px, -15px) rotate(90deg) scale(1.05);
    }
    50% {
        transform: translate(-5px, -25px) rotate(180deg) scale(1.1);
    }
    75% {
        transform: translate(-15px, -10px) rotate(270deg) scale(1.05);
    }
    100% {
        transform: translate(0, 0) rotate(360deg) scale(1);
    }
    }
    </style>

    <style>
        /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        transition: width 0.3s ease;
    }

    .custom-scrollbar:hover::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 3px;
        transition: background-color 0.3s ease;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
        animation: pulse-glow 1.5s infinite alternate;
    }

    /* Announcement animations */
    .custom-scrollbar > div {
        transform: translateY(20px);
        opacity: 0;
        animation: slide-up 0.5s forwards;
        animation-delay: calc(0.1s * var(--announcement-index, 0));
    }

    @keyframes slide-up {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes pulse-glow {
        0% {
            box-shadow: 0 0 0 rgba(148, 163, 184, 0.4);
        }
        100% {
            box-shadow: 0 0 10px rgba(148, 163, 184, 0.7);
        }
    }
    </style>

    <style>
    /* CTA Section Floating Particles */
    .cta-particle {
        animation: cta-float 15s infinite ease-in-out;
    }

    .cta-particle:nth-child(1) {
        animation-duration: 20s;
        animation-delay: 0s;
    }

    .cta-particle:nth-child(2) {
        animation-duration: 25s;
        animation-delay: -5s;
    }

    .cta-particle:nth-child(3) {
        animation-duration: 18s;
        animation-delay: -3s;
    }

    .cta-particle:nth-child(4) {
        animation-duration: 22s;
        animation-delay: -7s;
    }

    .cta-particle:nth-child(5) {
        animation-duration: 19s;
        animation-delay: -10s;
    }

    .cta-particle:nth-child(6) {
        animation-duration: 23s;
        animation-delay: -2s;
    }

    @keyframes cta-float {
        0% {
            transform: translate(0px, 0px) rotate(0deg) scale(1);
            opacity: 0.08;
        }
        25% {
            transform: translate(15px, -10px) rotate(5deg) scale(1.05);
            opacity: 0.1;
        }
        50% {
            transform: translate(5px, -20px) rotate(-5deg) scale(1.1);
            opacity: 0.12;
        }
        75% {
            transform: translate(-10px, -5px) rotate(3deg) scale(1.05);
            opacity: 0.1;
        }
        100% {
            transform: translate(0px, 0px) rotate(0deg) scale(1);
            opacity: 0.08;
        }
    }
    </style>

</body>
</html>