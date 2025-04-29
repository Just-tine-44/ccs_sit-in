<!-- filepath: c:\xampp\htdocs\login\admin\navbar_admin.php -->
<?php
// Define page titles
$pageTitle = "CCS Admin Panel"; // Default title
$currentFile = basename($_SERVER["PHP_SELF"]);

// Map filenames to their titles
$titles = [
    'admin_home.php' => 'CCS Admin Panel',
    'admin_search.php' => 'Student Search',
    'admin_student_list.php' => 'Student List',
    'sit_in.php' => 'Sit-in Management',
    'records.php' => 'Activity Records',
    'admin_reports.php' => 'System Reports',
    'sit-in_feedback.php' => 'Student Feedback',
];

// Define page icons
$pageIcons = [
    'admin_home.php' => 'fa-laptop-code',
    'admin_search.php' => 'fa-search',
    'admin_student_list.php' => 'fa-user-graduate',
    'sit_in.php' => 'fa-chair',
    'records.php' => 'fa-history',
    'admin_reports.php' => 'fa-chart-bar',
    'sit-in_feedback.php' => 'fa-comment-alt',
];

// Set the page title if it exists in our map
if (isset($titles[$currentFile])) {
    $pageTitle = $titles[$currentFile];
}

// Set the icon if it exists in our map
$icon = isset($pageIcons[$currentFile]) ? $pageIcons[$currentFile] : 'fa-laptop-code'; // Default icon
?>

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
                        <i class="fas <?php echo $icon; ?> text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-bold text-gray-800"><?php echo $pageTitle; ?></span>
                        <span class="hidden md:inline-block text-xs font-normal text-gray-500">
                            <?php echo ($currentFile === 'admin_home.php') ? 'Dashboard Overview' : 'CCS Administration'; ?>
                        </span>
                    </div>
                </a>
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <div id="nav-links" class="hidden md:flex md:items-center md:space-x-0.5 lg:space-x-2 flex-col md:flex-row w-full md:w-auto mt-4 md:mt-0">
                <a href="admin_home.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-home mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Home</span>
                </a>

                <a href="admin_search.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-search mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Search</span>
                </a>

                <a href="admin_student_list.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-user-graduate mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Students</span>
                </a>
                <a href="sit_in.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chair mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Sit-in</span>
                </a>
                <a href="records.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-history mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Records</span>
                </a>
                <a href="admin_reports.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chart-bar mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Reports</span>
                </a>
                <a href="sit-in_feedback.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-comment-alt mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Feedback</span>
                </a>
                <a href="admin_reservation.php" class="nav-link group px-2 py-2 rounded-lg flex items-center text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-calendar-check mr-1 text-gray-400 group-hover:text-blue-500"></i>
                    <span>Reservation</span>
                </a>

                   <!-- Theme Toggle Button -->
                <div class="px-2 mr-2 border-r border-gray-200 dark:border-gray-700">
                    <button id="themeToggle" class="p-2 rounded-full hover:bg-gray-100 hover:bg-opacity-50 transition-colors">
                        <!-- Sun icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 theme-icon-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 theme-icon-dark hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
                
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
    
    // Set active nav link and update page title in browser
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
                
                // Also update document title
                const pageName = link.querySelector('span').textContent;
                document.title = pageName + " - CCS Admin";
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
                // Show a minor alert that logout was cancelled
                const pageUrl = window.location.href.split('/').pop();
                window.location.href = pageUrl || 'admin_home.php';
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
    
    /* Animate title changes */
    .flex-col span {
        transition: all 0.3s ease;
    }
    
    /* Make active nav more obvious */
    .nav-link.text-blue-600 {
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

<style>
    /* Dark mode styles - Improved for better contrast and readability */
    :root {
        --light-bg: #f9fafb;
        --light-card: #ffffff;
        --light-text: #374151;
        --light-text-secondary: #6B7280;
        --light-border: #e5e7eb;
        --dark-bg: #111827;
        --dark-card: #1f2937;
        --dark-card-secondary: #374151;
        --dark-text: #f3f4f6;
        --dark-text-secondary: #d1d5db;
        --dark-border: #374151;
    }

    body.dark-mode {
        background-color: var(--dark-bg);
        color: var(--dark-text);
    }

    /* Card backgrounds */
    body.dark-mode .bg-white {
        background-color: var(--dark-card);
    }

    body.dark-mode .bg-gray-50,
    body.dark-mode .bg-gray-100 {
        background-color: var(--dark-card-secondary);
    }

    /* Text colors */
    body.dark-mode .text-gray-700,
    body.dark-mode .text-gray-800,
    body.dark-mode .text-gray-900 {
        color: var(--dark-text);
    }

    body.dark-mode .text-gray-500,
    body.dark-mode .text-gray-600 {
        color: var(--dark-text-secondary);
    }

    /* Borders */
    body.dark-mode .border-gray-100,
    body.dark-mode .border-gray-200 {
        border-color: var(--dark-border);
    }

    /* Navbar-specific */
    body.dark-mode nav.bg-white {
        background-color: var(--dark-card);
    }
    
    body.dark-mode .nav-link.hover\:bg-blue-50:hover {
        background-color: rgba(59, 130, 246, 0.2);
    }

    /* Improved Table Elements */
    body.dark-mode #recordsTable thead,
    body.dark-mode #reservationsTable thead,
    body.dark-mode table thead {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    body.dark-mode #recordsTable thead th,
    body.dark-mode #reservationsTable thead th,
    body.dark-mode table thead th {
        color: #e5e7eb !important;
        border-bottom-color: #374151 !important;
    }

    /* Table body cells */
    body.dark-mode #recordsTable tbody td,
    body.dark-mode #reservationsTable tbody td,
    body.dark-mode table tbody td {
        border-color: #374151 !important;
        color: #e5e7eb !important;
    }

    body.dark-mode table tbody tr:hover {
        background-color: #2d3748 !important;
    }
    
    body.dark-mode .bg-white tbody {
        background-color: #1f2937 !important;
    }
    
    body.dark-mode .bg-white tbody tr {
        background-color: #1f2937 !important;
    }

    /* Force the text color in the table cells that use text-gray-500 */
    body.dark-mode table .text-sm.text-gray-500 {
        color: #d1d5db !important;
    }

    /* Input Fields */
    body.dark-mode input, 
    body.dark-mode textarea, 
    body.dark-mode select {
        background-color: #1f2937;
        border-color: #4b5563;
        color: var(--dark-text);
    }
    
    body.dark-mode input::placeholder, 
    body.dark-mode textarea::placeholder {
        color: #9ca3af;
    }

    /* Button Improvements */
    body.dark-mode .bg-blue-50 {
        background-color: rgba(59, 130, 246, 0.15);
    }
    
    body.dark-mode .bg-red-50 {
        background-color: rgba(239, 68, 68, 0.15);
    }
    
    body.dark-mode .bg-green-50 {
        background-color: rgba(16, 185, 129, 0.15);
    }
    
    body.dark-mode .bg-yellow-50 {
        background-color: rgba(245, 158, 11, 0.15);
    }
    
    body.dark-mode .bg-purple-50 {
        background-color: rgba(139, 92, 246, 0.15);
    }
    
    body.dark-mode .hover\:bg-gray-100:hover {
        background-color: #374151 !important;
    }

    /* Charts and Graphs */
    body.dark-mode canvas {
        filter: brightness(0.9) contrast(1.1);
    }
    
    /* Status labels - ensure they remain visible */
    body.dark-mode .bg-green-100 {
        background-color: rgba(16, 185, 129, 0.2) !important;
    }
    
    body.dark-mode .text-green-800 {
        color: #4ade80 !important;
    }
    
    body.dark-mode .bg-red-100 {
        background-color: rgba(239, 68, 68, 0.2) !important;
    }
    
    body.dark-mode .text-red-800 {
        color: #f87171 !important;
    }
    
    body.dark-mode .bg-yellow-100 {
        background-color: rgba(245, 158, 11, 0.2) !important;
    }
    
    body.dark-mode .text-yellow-800 {
        color: #fcd34d !important;
    }
    
    body.dark-mode .bg-blue-100 {
        background-color: rgba(59, 130, 246, 0.2) !important;
    }
    
    body.dark-mode .text-blue-800 {
        color: #60a5fa !important;
    }

    body.dark-mode .bg-purple-100 {
        background-color: rgba(139, 92, 246, 0.2) !important;
    }

    body.dark-mode .text-purple-800 {
        color: #a78bfa !important;
    }
    
    body.dark-mode .bg-gray-100 {
        background-color: rgba(107, 114, 128, 0.2) !important;
    }
    
    /* Scrollbar styling */
    body.dark-mode .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937 !important;
    }

    body.dark-mode .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #4b5563 !important;
    }

    body.dark-mode .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #6b7280 !important;
    }
    
    /* Keep status colors visible with brightness adjustment */
    body.dark-mode .bg-green-500,
    body.dark-mode .bg-red-500,
    body.dark-mode .bg-purple-500,
    body.dark-mode .bg-blue-500,
    body.dark-mode .bg-yellow-500 {
        filter: brightness(1.2);
    }
    
    /* Dashboard cards and special elements */
    body.dark-mode .border-l-4 {
        border-left-width: 4px !important;
    }
    
    body.dark-mode .bg-gradient-to-r {
        opacity: 0.9;
    }
    
    /* Fix for dropdown menus */
    body.dark-mode #exportDropdown {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }

    body.dark-mode #exportDropdown button {
        color: #e5e7eb !important;
    }

    body.dark-mode #exportDropdown button:hover {
        background-color: #374151 !important;
    }
    
    /* Fix "No records found" display */
    body.dark-mode .text-center.py-10 .bg-gray-100 {
        background-color: #374151 !important;
    }

    body.dark-mode .text-center.py-10 .text-gray-400 {
        color: #9ca3af !important;
    }
    
    /* Fix tabs */
    body.dark-mode .tab-inactive {
        color: #9ca3af !important;
    }
    
    body.dark-mode .tab-active {
        color: #60a5fa !important;
        border-bottom-color: #60a5fa !important;
    }
    
    /* Fix the form inputs better */
    body.dark-mode select,
    body.dark-mode input[type="text"],
    body.dark-mode input[type="date"],
    body.dark-mode input[type="password"],
    body.dark-mode input[type="email"],
    body.dark-mode input[type="number"] {
        background-color: #374151 !important;
        color: #e5e7eb !important;
        border-color: #4b5563 !important;
    }

    body.dark-mode select option {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
    }

    body.dark-mode label {
        color: #e5e7eb !important;
    }
    
    /* Specifically target tables in records.php */
    body.dark-mode #records-tab table,
    body.dark-mode #reservations-tab table {
        background-color: #1f2937 !important;
    }
    
    body.dark-mode #records-tab table thead,
    body.dark-mode #reservations-tab table thead {
        background-color: #1f2937 !important;
    }

    /* Handle badges better */
    body.dark-mode .text-sm.font-medium.inline-flex.items-center.px-2\.5.py-0\.5.rounded-full {
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* SweetAlert in dark mode */
    body.dark-mode .swal2-popup {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
    }

    body.dark-mode .swal2-title {
        color: #f3f4f6 !important;
    }

    body.dark-mode .swal2-content {
        color: #d1d5db !important;
    }

        /* Dark mode styles for the feedback modal */
    body.dark-mode #feedbackModal .bg-white {
        background-color: #1f2937;
        color: #e5e7eb;
    }

    body.dark-mode #feedbackModal .text-gray-800 {
        color: #f3f4f6;
    }

    body.dark-mode #feedbackModal .text-gray-500 {
        color: #d1d5db;
    }

    body.dark-mode #feedbackModal .border-gray-200 {
        border-color: #374151;
    }

    body.dark-mode #feedbackModal .bg-gray-50 {
        background-color: #111827;
    }

    body.dark-mode #feedbackModal .bg-gray-200 {
        background-color: #4b5563;
    }

    body.dark-mode #feedbackModal .text-gray-700 {
        color: #e5e7eb;
    }

    /* Specifically target the close button */
    body.dark-mode #feedbackModal button.text-gray-500 {
        color: #d1d5db !important;
    }

    body.dark-mode #feedbackModal button.text-gray-500:hover {
        color: #f3f4f6 !important;
    }

    /* Make the modal content more visible in dark mode */
    body.dark-mode #modal-feedback-text {
        color: #e5e7eb !important;
    }
</style>

<script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {

    });
</script>

<script src="theme.js"></script>