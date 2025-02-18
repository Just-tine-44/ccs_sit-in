<nav class="bg-white shadow mb-8 font-sans">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="homepage.php" class="text-gray-800 font-bold text-xl hover:text-blue-500 relative">UC-CCS</a>
            <div class="flex space-x-4 items-center">
                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()" class="text-gray-800 hover:text-blue-500 relative">
                        Notifications <span class="ml-1 text-lg">&#9662;</span>
                    </button>
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-md">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">No new notifications</a>
                    </div>
                </div>
                
                <a href="homepage.php" class="text-gray-800 hover:text-blue-500 relative">Home</a>
                <a href="edit.php" class="text-gray-800 hover:text-blue-500 relative">Edit Profile</a>
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">History</a>
                <a href="#" class="text-gray-800 hover:text-blue-500 relative">Reservation</a>
                <a href="javascript:void(0);" onclick="logout()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </div>
</nav>

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

<script>
    function toggleDropdown() {
        document.getElementById("notificationDropdown").classList.toggle("hidden");
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        let dropdown = document.getElementById("notificationDropdown");
        if (!event.target.closest(".relative")) {
            dropdown.classList.add("hidden");
        }
    });
    
    function logout() {
        window.location.href = 'logout.php';
    }
</script>