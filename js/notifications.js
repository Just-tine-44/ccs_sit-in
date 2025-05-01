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
    getLocalNotifications: function() {
      const stored = localStorage.getItem('userNotifications');
      return stored ? JSON.parse(stored) : [];
    },
    
    // Fetch notifications from server and combine with local notifications
    fetchNotifications: function() {
      fetch('../connection/fetch_notifications.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            // Get existing notifications from localStorage
            const localNotifications = this.getLocalNotifications();
            
            // Create a map of existing server notification IDs
            const serverNotificationIds = new Set(data.notifications.map(n => n.id));
            
            // Keep local notifications that don't exist on server and have client-generated IDs
            // Client IDs are typically timestamps (large numbers) while server IDs start from 1
            const localOnlyNotifications = localNotifications.filter(n => 
              !serverNotificationIds.has(n.id) && (typeof n.id === 'number' && n.id > 1000000000)
            );
            
            console.log("Server notifications:", data.notifications.length);
            console.log("Local-only notifications:", localOnlyNotifications.length);
            
            // Combine server and local-only notifications
            const combinedNotifications = [...data.notifications, ...localOnlyNotifications];
            
            // Sort by creation date (newest first)
            combinedNotifications.sort((a, b) => {
              return new Date(b.createdAt) - new Date(a.createdAt);
            });
            
            // Calculate total unread count for combined notifications
            const unreadCount = combinedNotifications.filter(n => !n.isRead).length;
            
            // Update badge count
            this.updateNotificationBadge(unreadCount);
            
            // Render notifications in dropdown
            this.renderServerNotifications(combinedNotifications);
            
            // Save combined notifications to localStorage for offline use
            localStorage.setItem('userNotifications', JSON.stringify(combinedNotifications));
            
            // Check for new server notifications to show toast
            // Only show toast for server notifications that don't exist in the local notifications
            // and are not marked as read
            const newServerNotifications = data.notifications.filter(serverNotif => 
              !serverNotif.isRead && 
              !localNotifications.some(localNotif => localNotif.id === serverNotif.id)
            );
            
            // Show toast for the newest unread notification
            if (newServerNotifications.length > 0) {
              // Sort by created date and get the newest
              newServerNotifications.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
              const newestNotif = newServerNotifications[0];
              
              Toast.fire({
                icon: newestNotif.type,
                title: newestNotif.title,
                text: newestNotif.message
              });
            }
          }
        })
        .catch(error => {
          console.error('Error fetching notifications:', error);
          
          // Fallback to localStorage notifications when server is unavailable
          const localNotifications = this.getLocalNotifications();
          this.updateNotificationBadge(localNotifications.filter(n => !n.isRead).length);
          this.renderLocalNotifications();
        });
    },
  
    // Add a new notification (client-side only, for immediate feedback)
    addNotification: function(title, message, type = 'info', relatedId = null) {
      // Get existing notifications
      const notifications = this.getLocalNotifications();
      
      // Create new notification object with a unique client-side ID
      // Use Date.now() to ensure it's different from server IDs
      const newNotification = {
        id: Date.now(),
        title: title,
        message: message,
        type: type,
        isRead: false,
        relatedId: relatedId,
        createdAt: new Date().toISOString(),
        isLocalOnly: true // Flag to identify client-side notifications
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
      this.updateNotificationBadge(notifications.filter(n => !n.isRead).length);
      this.renderLocalNotifications();
      
      return newNotification;
    },
    
    // Mark a notification as read (handles both server and local notifications)
    markAsRead: function(id) {
      // Get existing notifications to check if this is a local-only notification
      const notifications = this.getLocalNotifications();
      const notificationToUpdate = notifications.find(n => n.id === id);
      
      // If it's a server notification (smaller ID) or we can't tell, try server first
      if (!notificationToUpdate || !notificationToUpdate.isLocalOnly) {
        const formData = new FormData();
        formData.append('mark_read', '1');
        formData.append('notification_id', id);
        
        fetch('../connection/fetch_notifications.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Refresh notifications from server and combine with local
            this.fetchNotifications();
          }
        })
        .catch(error => {
          console.error('Error marking notification as read:', error);
          // Fall back to local update
          this.markLocalNotificationAsRead(id);
        });
      } else {
        // It's definitely a local notification, update locally
        this.markLocalNotificationAsRead(id);
      }
    },
    
    // Helper method to mark a local notification as read
    markLocalNotificationAsRead: function(id) {
      const notifications = this.getLocalNotifications();
      
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
      this.updateNotificationBadge(updatedNotifications.filter(n => !n.isRead).length);
      this.renderLocalNotifications();
    },
    
    // Mark all notifications as read (both server and local)
    markAllAsRead: function() {
      // First, mark all server notifications as read
      const formData = new FormData();
      formData.append('mark_read', 'all');
      
      fetch('../connection/fetch_notifications.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Now mark all local notifications as read
        const notifications = this.getLocalNotifications();
        
        // Mark all as read
        const updatedNotifications = notifications.map(notification => {
          return { ...notification, isRead: true };
        });
        
        // Save updated notifications
        localStorage.setItem('userNotifications', JSON.stringify(updatedNotifications));
        
        // Update UI
        this.updateNotificationBadge(0);
        this.renderLocalNotifications();
        
        // Finally refresh from server to get the updated combined state
        this.fetchNotifications();
      })
      .catch(error => {
        console.error('Error marking all notifications as read:', error);
        
        // Fallback to localStorage for offline use
        const notifications = this.getLocalNotifications();
        
        // Mark all as read
        const updatedNotifications = notifications.map(notification => {
          return { ...notification, isRead: true };
        });
        
        // Save updated notifications
        localStorage.setItem('userNotifications', JSON.stringify(updatedNotifications));
        
        // Update UI
        this.updateNotificationBadge(0);
        this.renderLocalNotifications();
      });
    },
    
    // Delete a notification (client-side only)
    deleteNotification: function(id) {
      let notifications = this.getLocalNotifications();
      
      // Remove the notification
      notifications = notifications.filter(notification => notification.id !== parseInt(id));
      
      // Save updated notifications
      localStorage.setItem('userNotifications', JSON.stringify(notifications));
      
      // Update UI
      this.updateNotificationBadge(notifications.filter(n => !n.isRead).length);
      this.renderLocalNotifications();
    },
    
    // Update notification badge count
    updateNotificationBadge: function(count) {
      const badge = document.getElementById('notificationBadge');
      if (badge) {
        badge.textContent = count > 0 ? count : '0';
        badge.style.display = count > 0 ? 'flex' : 'none';
      }
      
      // Update count in header if it exists
      const headerCount = document.querySelector('.notification-header-count');
      if (headerCount) {
        headerCount.textContent = count > 0 ? count + ' new' : 'No new';
        
        if (count > 0) {
          headerCount.classList.add('bg-blue-100', 'text-blue-600');
          headerCount.classList.remove('bg-gray-100', 'text-gray-600');
        } else {
          headerCount.classList.add('bg-gray-100', 'text-gray-600');
          headerCount.classList.remove('bg-blue-100', 'text-blue-600');
        }
      }
    },
    
    // Render notifications from server in dropdown
    renderServerNotifications: function(notifications) {
      const container = document.getElementById('notificationContainer');
      if (!container) return;
      
      // If no notifications, show empty state
      if (!notifications || notifications.length === 0) {
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
    
    // Render notifications from localStorage (fallback)
    renderLocalNotifications: function() {
      const notifications = this.getLocalNotifications();
      const container = document.getElementById('notificationContainer');
      if (!container) return;
      
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
      console.log('Initializing notification system...');
      
      // Initial fetch of notifications from server
      this.fetchNotifications();
      
      // Set up polling to check for new notifications every 30 seconds
      setInterval(() => {
        this.fetchNotifications();
      }, 30000);
      
      // Add listener for notification dropdown toggle
      const toggleButton = document.querySelector('.notification-btn');
      if (toggleButton) {
        toggleButton.addEventListener('click', () => {
          // Refresh notifications when dropdown is opened
          this.fetchNotifications();
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
      console.log('Attempting to load SweetAlert2 dynamically...');
      
      // Try to load SweetAlert2 dynamically
      const sweetAlertScript = document.createElement('script');
      sweetAlertScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
      sweetAlertScript.onload = function() {
        console.log('SweetAlert2 loaded successfully, initializing notifications system');
        initNotificationsSystem();
      };
      document.head.appendChild(sweetAlertScript);
      
      // Set a global empty notifications system to prevent errors in case load fails
      window.notificationsSystem = {
        addNotification: function() { 
          console.warn('Notifications system not available'); 
          return null;
        },
        markAsRead: function() {},
        markAllAsRead: function() {},
        deleteNotification: function() {},
        getLocalNotifications: function() { return []; },
        fetchNotifications: function() {},
        updateNotificationBadge: function() {},
        renderServerNotifications: function() {},
        renderLocalNotifications: function() {},
        init: function() {}
      };
    }
  });
}