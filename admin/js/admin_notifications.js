// Admin Notifications System
const adminNotificationSystem = {
    // Get all notifications from localStorage
    getNotifications: function() {
        const stored = localStorage.getItem('adminNotifications');
        return stored ? JSON.parse(stored) : [];
    },

    // Add a new notification
    addNotification: function(title, message, type = 'info', relatedId = null) {
        // Get existing notifications
        const notifications = this.getNotifications();
        
        // Create new notification object
        const newNotification = {
            id: Date.now(),
            title: title,
            message: message,
            type: type,
            isRead: false,
            relatedId: relatedId,
            createdAt: new Date().toISOString()
        };
        
        // Add to beginning of array (newest first)
        notifications.unshift(newNotification);
        
        // Keep only the most recent 50 notifications
        const trimmedNotifications = notifications.slice(0, 50);
        
        // Save to localStorage
        localStorage.setItem('adminNotifications', JSON.stringify(trimmedNotifications));
        
        // Show toast notification if SweetAlert2 is available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: title,
                text: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
        
        // Update UI
        this.updateNotificationBadge();
        this.renderNotifications();
        
        return newNotification;
    },
    
    // Mark a notification as read
    markAsRead: function(id) {
        const notifications = this.getNotifications();
        
        // Update the notification
        const updatedNotifications = notifications.map(notification => {
            if (notification.id === parseInt(id)) {
                return { ...notification, isRead: true };
            }
            return notification;
        });
        
        // Save updated notifications
        localStorage.setItem('adminNotifications', JSON.stringify(updatedNotifications));
        
        // Update UI
        this.updateNotificationBadge();
        this.renderNotifications();
    },
    
    // Mark all notifications as read
    markAllAsRead: function() {
        const notifications = this.getNotifications();
        
        // Mark all as read
        const updatedNotifications = notifications.map(notification => {
            return { ...notification, isRead: true };
        });
        
        // Save updated notifications
        localStorage.setItem('adminNotifications', JSON.stringify(updatedNotifications));
        
        // Update UI
        this.updateNotificationBadge();
        this.renderNotifications();
    },
    
    // Update notification badge count
    updateNotificationBadge: function() {
        const notifications = this.getNotifications();
        const unreadCount = notifications.filter(n => !n.isRead).length;
        
        const badge = document.getElementById('adminNotificationBadge');
        if (badge) {
            badge.textContent = unreadCount > 0 ? unreadCount : '0';
            badge.style.display = unreadCount > 0 ? 'flex' : 'none';
        }
        
        // Update count in header
        const headerCount = document.getElementById('adminNotificationHeaderCount');
        if (headerCount) {
            headerCount.textContent = unreadCount > 0 ? unreadCount + ' new' : 'No new';
            
            if (unreadCount > 0) {
                headerCount.classList.add('bg-blue-100', 'text-blue-600');
                headerCount.classList.remove('bg-gray-100', 'text-gray-600');
            } else {
                headerCount.classList.add('bg-gray-100', 'text-gray-600');
                headerCount.classList.remove('bg-blue-100', 'text-blue-600');
            }
        }
    },
    
    // Render notifications in dropdown
    renderNotifications: function() {
        const container = document.getElementById('adminNotificationContainer');
        if (!container) return;
        
        const notifications = this.getNotifications();
        
        // If no notifications, show empty state
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="px-4 py-6 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
                    <p>No notifications yet</p>
                </div>
            `;
            return;
        }
        
        // Otherwise show all notifications
        container.innerHTML = notifications.map(notification => {
            const date = new Date(notification.createdAt);
            const timeAgo = this.getTimeAgo(date);
            
            let iconClass = 'fa-info-circle';
            let bgColorClass = 'bg-blue-100'; 
            let textColorClass = 'text-blue-500';
            
            switch(notification.type) {
                case 'success':
                    iconClass = 'fa-check-circle';
                    bgColorClass = 'bg-green-100';
                    textColorClass = 'text-green-500';
                    break;
                case 'warning':
                    iconClass = 'fa-exclamation-triangle';
                    bgColorClass = 'bg-yellow-100';
                    textColorClass = 'text-yellow-500';
                    break;
                case 'error':
                    iconClass = 'fa-times-circle';
                    bgColorClass = 'bg-red-100';
                    textColorClass = 'text-red-500';
                    break;
            }
            
            return `
                <a href="javascript:void(0);" onclick="adminNotificationSystem.markAsRead(${notification.id})" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 ${!notification.isRead ? 'bg-blue-50' : ''}">
                    <div class="flex">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full ${bgColorClass} flex items-center justify-center ${textColorClass}">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        <div class="ml-3 flex-grow">
                            <p class="text-sm font-medium ${!notification.isRead ? 'text-gray-900' : 'text-gray-700'}">${notification.title}</p>
                            <p class="text-xs text-gray-600 mt-1">${notification.message}</p>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">${timeAgo}</p>
                                ${!notification.isRead ? '<span class="h-2 w-2 bg-blue-500 rounded-full"></span>' : ''}
                            </div>
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    },
    
    // Get time ago string
    getTimeAgo: function(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 31536000);
        
        if (interval >= 1) {
            return interval + ' year' + (interval === 1 ? '' : 's') + ' ago';
        }
        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) {
            return interval + ' month' + (interval === 1 ? '' : 's') + ' ago';
        }
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) {
            return interval + ' day' + (interval === 1 ? '' : 's') + ' ago';
        }
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) {
            return interval + ' hour' + (interval === 1 ? '' : 's') + ' ago';
        }
        interval = Math.floor(seconds / 60);
        if (interval >= 1) {
            return interval + ' minute' + (interval === 1 ? '' : 's') + ' ago';
        }
        return 'just now';
    },
    
    // Check for new reservation requests
    checkNewReservations: function() {
        // Get the last check time from localStorage
        const lastCheck = localStorage.getItem('lastReservationCheck') || '0';
        
        // Get list of already shown notification IDs
        const shownNotifications = localStorage.getItem('shownNotificationIds') || '[]';
        const shownIds = JSON.parse(shownNotifications);
        
        // Include the already shown IDs in the request to filter them out
        fetch(`conn_back/check_reservations.php?last_check=${lastCheck}&shown_ids=${encodeURIComponent(JSON.stringify(shownIds))}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update last check time
                localStorage.setItem('lastReservationCheck', data.currentTime);
                
                // Process each new notification from server
                if (data.notifications && data.notifications.length > 0) {
                    // Track the new IDs we're about to show
                    const newIds = data.notifications.map(n => n.id);
                    
                    // Update the list of shown IDs
                    const updatedShownIds = [...shownIds, ...newIds];
                    localStorage.setItem('shownNotificationIds', JSON.stringify(updatedShownIds));
                    
                    // Add each notification to the system
                    data.notifications.forEach(notification => {
                        this.addNotification(
                            notification.title,
                            notification.message,
                            notification.type || 'info',
                            notification.id
                        );
                    });
                    
                    // OPTIONAL: Show a summary notification if there are multiple
                    if (data.notifications.length > 1) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: `${data.notifications.length} New Reservation Requests`,
                            text: 'You have multiple new reservation requests to review',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true
                        });
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error checking for new reservations:', error);
        });
    },
    
    // Clear all notifications
    clearAllNotifications: function() {
        if (confirm('Are you sure you want to clear all notifications?')) {
            localStorage.removeItem('adminNotifications');
            this.updateNotificationBadge();
            this.renderNotifications();
        }
    },
    
    // Initialize notifications system
    init: function() {
        console.log('Initializing admin notification system...');
        this.updateNotificationBadge();
        this.renderNotifications();
        
        // Toggle dropdown visibility
        const notificationBtn = document.getElementById('notificationDropdownBtn');
        const notificationDropdown = document.getElementById('adminNotificationDropdown');
        
        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
                this.renderNotifications(); // Re-render when opened
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        }
        
        // Check for new reservations immediately
        this.checkNewReservations();
        
        // Set up polling to check for new reservations every 30 seconds
        setInterval(() => {
            this.checkNewReservations();
        }, 30000);
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    adminNotificationSystem.init();
});