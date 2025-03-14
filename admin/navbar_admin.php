<!-- filepath: c:\xampp\htdocs\login\admin\navbar_admin.php -->
<link href="../css/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>

<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3">
            <!-- Logo and Title -->
            <div class="flex items-center justify-between">
                <a href="admin_home.php" class="flex items-center space-x-3">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-laptop-code text-xl"></i>
                    </div>
                    <span class="text-lg font-bold text-gray-800">CCS Admin <span class="hidden md:inline-block text-sm font-normal text-gray-500">Panel</span></span>
                </a>
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <div id="nav-links" class="hidden md:flex md:items-center md:space-x-1 lg:space-x-4 flex-col md:flex-row w-full md:w-auto mt-4 md:mt-0">
                <a href="admin_home.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-home mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Home</span>
                </a>

                <a href="admin_search.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-search mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Search</span>
                </a>

                <a href="admin_student_list.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-user-graduate mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Students</span>
                </a>
                <a href="sit_in.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chair mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Sit-in</span>
                </a>
                <a href="records.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-history mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Records</span>
                </a>
                <a href="admin_reports.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chart-bar mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Reports</span>
                </a>
                <a href="sit-in_feedback.php" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-comment-alt mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Feedback</span>
                </a>
                <a href="#" class="nav-link group px-3 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-calendar-check mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Reservation</span>
                </a>
                
                <!-- User menu -->
                <div class="relative ml-0 md:ml-2 mt-2 md:mt-0">
                    <button onclick="confirmLogout()" class="flex items-center justify-center space-x-1 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const navLinks = document.getElementById('nav-links');
        navLinks.classList.toggle('hidden');
    });
    
    // Set active nav link
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '#') {
                link.classList.add('bg-blue-50', 'text-blue-600');
                link.classList.remove('text-gray-700');
                const icon = link.querySelector('i');
                if (icon) icon.classList.add('text-blue-500');
                if (icon) icon.classList.remove('text-gray-400');
            }
        });
    });
    
    // SweetAlert2 logout confirmation
    function confirmLogout() {
        Swal.fire({
            title: 'Confirm Logout',
            text: 'Are you sure you want to logout from the admin panel?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            footer: '<small class="text-gray-500"><i class="fas fa-info-circle mr-1"></i> CCS-ADMIN PANEL</small>',
            backdrop: true,
            allowOutsideClick: true,
            customClass: {
                popup: 'rounded-lg',
                header: 'border-b border-gray-100',
                confirmButton: 'focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md',
                cancelButton: 'focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed logout
                logout();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = 'admin_home.php';
            }
        });
    }
    
    function logout() {
        // Show a loading state while processing logout
        Swal.fire({
            title: 'Logging out...',
            text: 'Please wait',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                setTimeout(() => {
                    window.location.href = 'logout_admin.php';
                }, 800);
            }
        });
    }
</script>

<style>
    /* Remove margin after navbar */
    nav.mb-8 {
        margin-bottom: 0 !important;
    }
    
    /* SweetAlert customization */
    .swal2-popup {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }
</style>