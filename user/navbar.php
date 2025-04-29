<nav class="bg-white shadow mb-8 font-sans">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center py-4">
            <div class="flex items-center w-full md:w-auto justify-between">
            <div class="flex items-center">
                <img src="../images/ccslogo.png" alt="CCS Logo" class="h-8 w-auto mr-2">
                <?php
                    // Get current page
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    
                    // Set title based on current page
                    $pageTitle = "Dashboard"; // Default title
                    
                    if ($currentPage == 'edit.php') {
                        $pageTitle = "Edit Profile";
                    } elseif ($currentPage == 'history.php') {
                        $pageTitle = "History";
                    } elseif ($currentPage == 'reservation.php') {
                        $pageTitle = "Reservation";
                    } elseif ($currentPage == 'user_resources.php') {
                        $pageTitle = "Lab Resources";
                    } elseif ($currentPage == 'lab_schedule.php') {
                        $pageTitle = "Lab Schedule";
                    } elseif ($currentPage == 'reservation.php') {
                        $pageTitle = "Reservation";
                    }
                    
                ?>
                <a href="#" class="text-gray-800 font-bold text-xl hover:text-blue-500 transition-colors duration-200 <?= $currentPage == 'homepage.php' ? 'active' : '' ?>">
                    <?php echo $pageTitle; ?>
                </a>
            </div>
                <button id="navMenuToggle" class="md:hidden text-gray-800 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            
            <div id="navMobileMenu" class="hidden md:flex flex-col md:flex-row md:space-x-4 space-y-3 md:space-y-0 items-center w-full md:w-auto mt-4 md:mt-0 border-t md:border-t-0 pt-3 md:pt-0 transition-all duration-300">
                <!-- Notifications Dropdown -->
                <div class="relative w-full md:w-auto">
                    <button onclick="navToggleDropdown()" class="notification-btn flex items-center w-full md:w-auto justify-center md:justify-start text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors duration-200 group">
                        <i class="fas fa-bell mr-1 relative">
                            <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full flex items-center justify-center text-xs text-white">2</span>
                        </i>
                        <span class="inline">Notifications</span>
                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200" id="navNotifArrow"></i>
                        <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600 absolute bottom-0 left-0 right-0"></span>
                    </button>
                    <div id="navNotificationDropdown" class="hidden absolute right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-20 overflow-hidden w-80 md:w-80 sm:w-full">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">Recent Notifications</h3>
                                <span class="bg-blue-100 text-blue-600 text-xs font-medium px-2 py-0.5 rounded-full">2 new</span>
                            </div>
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            <!-- Notification Item - Unread -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 bg-blue-50">
                                <div class="flex">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="ml-3 flex-grow">
                                        <p class="text-sm font-medium text-gray-900">Your session in Room 303 starts in 15 minutes</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-xs text-gray-500">5 minutes ago</p>
                                            <span class="h-2 w-2 bg-blue-500 rounded-full"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Notification Item - Unread -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 bg-blue-50">
                                <div class="flex">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="ml-3 flex-grow">
                                        <p class="text-sm font-medium text-gray-900">Your reservation for Room 202 was approved</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-xs text-gray-500">1 hour ago</p>
                                            <span class="h-2 w-2 bg-blue-500 rounded-full"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Notification Item - Read -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100">
                                <div class="flex">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">Your session count is running low (5 remaining)</p>
                                        <p class="text-xs text-gray-500">Yesterday at 4:30 PM</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Notification Item - Read -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">System maintenance scheduled for Saturday</p>
                                        <p class="text-xs text-gray-500">3 days ago</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <a href="#" class="block bg-gray-50 text-center text-sm font-medium text-blue-600 hover:text-blue-700 py-2 border-t border-gray-100">
                            View all notifications
                        </a>
                    </div>
                </div>
                
                <a href="homepage.php" class="nav-item group w-full md:w-auto text-center md:text-left px-1 py-2 rounded-lg transition-all duration-200 <?= basename($_SERVER['PHP_SELF']) == 'homepage.php' ? 'active' : '' ?>">
                    <i class="fas fa-home mr-1"></i> Home
                    <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600"></span>
                </a>
                
                <a href="edit.php" class="nav-item group w-full md:w-auto text-center md:text-left px-1 py-2 rounded-lg transition-all duration-200 <?= basename($_SERVER['PHP_SELF']) == 'edit.php' ? 'active' : '' ?>">
                    <i class="fas fa-user-edit mr-1"></i> Edit Profile
                    <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600"></span>
                </a>
                
                <a href="history.php" class="nav-item group w-full md:w-auto text-center md:text-left px-1 py-2 rounded-lg transition-all duration-200 <?= basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : '' ?>">
                    <i class="fas fa-history mr-1"></i> History
                    <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600"></span>
                </a>

                <a href="user_resources.php" class="nav-item group w-full md:w-auto text-center md:text-left px-1 py-2 rounded-lg transition-all duration-200 <?= basename($_SERVER['PHP_SELF']) == 'user_resources.php' ? 'active' : '' ?>">
                    <i class="fas fa-book-reader mr-1"></i> Lab Resources
                    <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600"></span>
                </a>

                <a href="lab_schedule.php" class="nav-item group w-full md:w-auto text-center md:text-left px-1 py-2 rounded-lg transition-all duration-200 <?= basename($_SERVER['PHP_SELF']) == 'lab_schedule.php' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt mr-1"></i> View Lab Schedule
                    <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600"></span>
                </a>
                                
                <a href="reservation.php" class="nav-item group w-full md:w-auto text-center md:text-left px-1 py-2 rounded-lg transition-all duration-200 <?= basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check mr-1"></i> Reservation
                    <span class="hidden md:block max-w-0 group-hover:max-w-full transition-all duration-300 h-0.5 bg-blue-600"></span>
                </a>
                
                <a href="javascript:void(0);" onclick="navLogout()" class="logout-btn w-full md:w-auto text-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>


<style>
    .nav-item {
        color: #1a202c; 
        position: relative;
        overflow: hidden;
    }

    .nav-item:hover {
        background-color: rgba(240, 244, 248, 0.5); 
        transform: translateY(-1px);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .nav-item.active {
        color: #3182ce; 
        background-color: rgba(49, 130, 206, 0.1); 
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(49, 130, 206, 0.15); 
    }
    
    .nav-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #4299e1, #7f9cf5);
    }
    
    .logout-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .logout-btn:hover {
        transform: translateY(-2px);
    }
    
    .logout-btn::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.2);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
        z-index: 1;
    }
    
    .logout-btn:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
    
    .logout-btn i,
    .logout-btn span {
        position: relative;
        z-index: 2;
    }

    /* Notification dropdown styling */
    .notification-btn {
        position: relative;
        transition: all 0.2s ease;
    }

    .notification-btn:hover #navNotifArrow {
        transform: rotate(180deg);
    }

    #navNotificationDropdown {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        max-height: calc(100vh - 100px);
        animation: slideIn 0.2s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    #navNotificationDropdown a:last-child:hover {
        background-color: rgba(49, 130, 206, 0.05);
    }

    /* Mobile-specific styles */
    @media (max-width: 768px) {
        #navNotificationDropdown {
            width: calc(100vw - 2rem);
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            max-width: 24rem;
            margin-top: 0.5rem;
        }
        
        .nav-item.active::after {
            display: none;
        }
        
        .nav-item.active {
            background-color: rgba(49, 130, 206, 0.15);
            border-left: 3px solid #3182ce;
            padding-left: calc(0.75rem - 3px);
        }

        #navMobileMenu.flex {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize navbar functionality
    initNavbar();
});

function initNavbar() {
    // Add toggle menu event listener
    const menuToggle = document.getElementById('navMenuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            toggleMobileMenu();
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        const dropdown = document.getElementById("navNotificationDropdown");
        if (!dropdown) return;
        
        const toggleButton = event.target.closest(".notification-btn");
        
        if (!toggleButton && !dropdown.classList.contains("hidden") && !event.target.closest("#navNotificationDropdown")) {
            dropdown.classList.add("hidden");
            const arrow = document.getElementById("navNotifArrow");
            if (arrow) arrow.style.transform = "rotate(0deg)";
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const mobileMenu = document.getElementById('navMobileMenu');
        if (!mobileMenu) return;
        
        if (window.innerWidth >= 768 && mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('md:flex');
            mobileMenu.style.opacity = '1';
        }
    });
    
    // Add touch events for better mobile performance
    document.querySelectorAll('.notification-btn, .nav-item, .logout-btn').forEach(item => {
        item.addEventListener('touchstart', function() {}, {passive: true});
    });
}

function navToggleDropdown() {
    const dropdown = document.getElementById("navNotificationDropdown");
    if (!dropdown) return;
    
    const arrow = document.getElementById("navNotifArrow");
    
    dropdown.classList.toggle("hidden");
    
    if (!dropdown.classList.contains("hidden")) {
        if (arrow) arrow.style.transform = "rotate(180deg)";
        // Handle mobile positioning
        if (window.innerWidth < 768) {
            dropdown.style.width = "calc(100vw - 2rem)";
        }
    } else {
        if (arrow) arrow.style.transform = "rotate(0deg)";
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('navMobileMenu');
    if (!mobileMenu) return;
    
    if (mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.remove('hidden');
        mobileMenu.classList.add('flex');
        // Animation for appearing
        mobileMenu.style.opacity = '0';
        setTimeout(() => {
            mobileMenu.style.opacity = '1';
        }, 10);
    } else {
        // Animation for disappearing
        mobileMenu.style.opacity = '0';
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('flex');
            mobileMenu.style.opacity = '1'; // Reset for next time
        }, 200);
    }
}

function navLogout() {
    window.location.href = 'logout.php';
}
</script>