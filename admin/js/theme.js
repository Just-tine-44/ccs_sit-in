// Complete updated theme.js file

document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    
    // Check if elements exist
    if (!themeToggle) return;
    
    const lightIcon = document.querySelector('.theme-icon-light');
    const darkIcon = document.querySelector('.theme-icon-dark');
    
    if (!lightIcon || !darkIcon) return;
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    
    // Set initial theme
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        lightIcon.classList.add('hidden');
        darkIcon.classList.remove('hidden');
        
        // Add this meta tag for mobile devices
        updateMetaThemeColor('#111827');
        
        // Initial table fix for dark mode
        setTimeout(forceUpdateTableStyles, 100);
    } else {
        body.classList.remove('dark-mode');
        lightIcon.classList.remove('hidden');
        darkIcon.classList.add('hidden');
        
        // Restore default meta theme color
        updateMetaThemeColor('#f9fafb');
    }
    
    // Toggle theme on button click
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        // Toggle icons
        lightIcon.classList.toggle('hidden');
        darkIcon.classList.toggle('hidden');
        
        // Save preference
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            updateMetaThemeColor('#111827');
        } else {
            localStorage.setItem('theme', 'light');
            updateMetaThemeColor('#f9fafb');
        }
        
        // Force table updates
        forceUpdateTableStyles();
        
        // Force chart redraw if they exist (to fix chart colors in dark mode)
        if (typeof Chart !== 'undefined') {
            const charts = Chart.instances;
            for (let chartId in charts) {
                if (charts[chartId]) {
                    charts[chartId].update();
                }
            }
        }
        
        // Update SweetAlert if it's open
        updateSweetAlertTheme();
    });
    
    // Helper function to update meta theme-color for mobile browsers
    function updateMetaThemeColor(color) {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        metaThemeColor.content = color;
    }
    
    // Function to forcefully update all table elements when dark mode toggles
    function forceUpdateTableStyles() {
        // Table headers
        const tableHeaders = document.querySelectorAll('table thead');
        tableHeaders.forEach(header => {
            if (body.classList.contains('dark-mode')) {
                header.style.backgroundColor = '#1f2937';
                
                // Update header text
                const thCells = header.querySelectorAll('th');
                thCells.forEach(th => {
                    th.style.color = '#e5e7eb';
                });
            } else {
                header.style.backgroundColor = '';
                
                // Restore header text
                const thCells = header.querySelectorAll('th');
                thCells.forEach(th => {
                    th.style.color = '';
                });
            }
        });
        
        // Table rows
        const tableRows = document.querySelectorAll('table tbody tr');
        tableRows.forEach(row => {
            if (body.classList.contains('dark-mode')) {
                row.style.backgroundColor = '#1f2937';
            } else {
                row.style.backgroundColor = '';
            }
        });
        
        // Table cells text color
        const tableCells = document.querySelectorAll('table td');
        tableCells.forEach(cell => {
            if (body.classList.contains('dark-mode')) {
                if (cell.classList.contains('text-gray-500') || 
                    cell.classList.contains('text-gray-600') ||
                    cell.classList.contains('text-gray-700') ||
                    cell.classList.contains('text-gray-800')) {
                    cell.style.color = '#e5e7eb';
                }
            } else {
                cell.style.color = '';
            }
        });
        
        // Fix status badges
        const badges = document.querySelectorAll('.rounded-full');
        badges.forEach(badge => {
            if (body.classList.contains('dark-mode')) {
                // Make sure badges with light backgrounds are visible in dark mode
                if (badge.classList.contains('bg-green-100')) {
                    badge.style.backgroundColor = 'rgba(16, 185, 129, 0.2)';
                    const textEl = badge.querySelector('.text-green-800');
                    if (textEl) textEl.style.color = '#4ade80';
                } else if (badge.classList.contains('bg-red-100')) {
                    badge.style.backgroundColor = 'rgba(239, 68, 68, 0.2)';
                    const textEl = badge.querySelector('.text-red-800');
                    if (textEl) textEl.style.color = '#f87171';
                } else if (badge.classList.contains('bg-yellow-100')) {
                    badge.style.backgroundColor = 'rgba(245, 158, 11, 0.2)';
                    const textEl = badge.querySelector('.text-yellow-800');
                    if (textEl) textEl.style.color = '#fcd34d';
                } else if (badge.classList.contains('bg-blue-100')) {
                    badge.style.backgroundColor = 'rgba(59, 130, 246, 0.2)';
                    const textEl = badge.querySelector('.text-blue-800');
                    if (textEl) textEl.style.color = '#60a5fa';
                }
            } else {
                badge.style.backgroundColor = '';
                const textEls = badge.querySelectorAll('[class*="-800"]');
                textEls.forEach(el => {
                    el.style.color = '';
                });
            }
        });
    }
    
    // Update SweetAlert if it's open
    function updateSweetAlertTheme() {
        const swalContainer = document.querySelector('.swal2-container');
        if (swalContainer && swalContainer.querySelector('.swal2-popup')) {
            const swalPopup = swalContainer.querySelector('.swal2-popup');
            const swalTitle = swalPopup.querySelector('.swal2-title');
            const swalContent = swalPopup.querySelector('.swal2-content');
            
            if (body.classList.contains('dark-mode')) {
                swalPopup.style.backgroundColor = '#1f2937';
                swalPopup.style.color = '#e5e7eb';
                if (swalTitle) swalTitle.style.color = '#f3f4f6';
                if (swalContent) swalContent.style.color = '#d1d5db';
            } else {
                swalPopup.style.backgroundColor = '';
                swalPopup.style.color = '';
                if (swalTitle) swalTitle.style.color = '';
                if (swalContent) swalContent.style.color = '';
            }
        }
    }
});