// Initialize the notifications system when SweetAlert2 is available
function initNotificationsSystem() {
  // SweetAlert2 Toast configuration
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer);
      toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
  });
  
  // Make notifications system globally available
  window.notificationsSystem = {
    // Get all notifications from localStorage
    getNotifications: function() {
      const stored = localStorage.getItem('userNotifications');
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
      localStorage.setItem('userNotifications', JSON.stringify(trimmedNotifications));
      
      // Show toast notification
      Toast.fire({
        icon: type,
        title: title,
        text: message
      });
      
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
      localStorage.setItem('userNotifications', JSON.stringify(updatedNotifications));
      
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
      localStorage.setItem('userNotifications', JSON.stringify(updatedNotifications));
      
      // Update UI
      this.updateNotificationBadge();
      this.renderNotifications();
    },
    
    // Delete a notification
    deleteNotification: function(id) {
      let notifications = this.getNotifications();
      
      // Remove the notification
      notifications = notifications.filter(notification => notification.id !== parseInt(id));
      
      // Save updated notifications
      localStorage.setItem('userNotifications', JSON.stringify(notifications));
      
      // Update UI
      this.updateNotificationBadge();
      this.renderNotifications();
    },
    
    // Update notification badge count
    updateNotificationBadge: function() {
      const notifications = this.getNotifications();
      const unreadCount = notifications.filter(n => !n.isRead).length;
      
      const badge = document.getElementById('notificationBadge');
      if (badge) {
        badge.textContent = unreadCount > 0 ? unreadCount : '0';
        badge.style.display = unreadCount > 0 ? 'flex' : 'none';
      }
    },
    
    // Render notifications in dropdown
    renderNotifications: function() {
      const container = document.getElementById('notificationContainer');
      if (!container) return;
      
      const notifications = this.getNotifications();
      
      // Update count in header if it exists
      const headerCount = document.querySelector('.notification-header-count');
      if (headerCount) {
        const unreadCount = notifications.filter(n => !n.isRead).length;
        headerCount.textContent = unreadCount > 0 ? unreadCount + ' new' : 'No new';
        
        if (unreadCount > 0) {
          headerCount.classList.add('bg-blue-100', 'text-blue-600');
          headerCount.classList.remove('bg-gray-100', 'text-gray-600');
        } else {
          headerCount.classList.add('bg-gray-100', 'text-gray-600');
          headerCount.classList.remove('bg-blue-100', 'text-blue-600');
        }
      }
      
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
          <a href="javascript:void(0);" onclick="window.notificationsSystem.markAsRead(${notification.id})" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 ${!notification.isRead ? 'bg-blue-50' : ''}">
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
    
    // Initialize notifications system
    init: function() {
      this.updateNotificationBadge();
      this.renderNotifications();
      
      // Add listener for notification dropdown toggle
      const toggleButton = document.querySelector('.notification-btn');
      if (toggleButton) {
        toggleButton.addEventListener('click', () => {
          this.renderNotifications();
        });
      }
    }
  };
  
  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    window.notificationsSystem.init();
  });

  console.log('Notifications system initialized successfully');
}

// Check if SweetAlert is already loaded
if (typeof Swal !== 'undefined') {
  // SweetAlert2 is available, initialize now
  initNotificationsSystem();
} else {
  // Wait for SweetAlert2 to load
  document.addEventListener('DOMContentLoaded', function() {
    // Check again after DOM is loaded
    if (typeof Swal !== 'undefined') {
      initNotificationsSystem();
    } else {
      console.error('SweetAlert2 is not loaded. Notifications will not work.');
      
      // Set a global empty notifications system to prevent errors
      window.notificationsSystem = {
        addNotification: function() { 
          console.warn('Notifications system not available'); 
          return null;
        },
        markAsRead: function() {},
        markAllAsRead: function() {},
        deleteNotification: function() {},
        getNotifications: function() { return []; },
        updateNotificationBadge: function() {},
        renderNotifications: function() {},
        init: function() {}
      };
    }
  });
}