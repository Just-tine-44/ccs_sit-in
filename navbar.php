<nav class="bg-white shadow mb-8 font-sans">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="homepage.php" class="text-gray-800 font-bold text-xl hover:text-blue-500 relative">Dashboard</a>
            <div class="flex space-x-4 items-center">
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
function logout() {
    window.location.href = 'logout.php';
}
</script>