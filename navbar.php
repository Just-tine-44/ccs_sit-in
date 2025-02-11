<nav class="bg-white shadow mb-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="homepage.php" class="text-gray-800 font-bold text-xl hover:text-blue-500">Dashboard</a>
            <div class="flex space-x-4 items-center">
                <a href="homepage.php" class="text-gray-800 hover:text-blue-500">Home</a>
                <a href="edit.php" class="text-gray-800 hover:text-blue-500">Edit Profile</a>
                <a href="#" class="text-gray-800 hover:text-blue-500">History</a>
                <a href="#" class="text-gray-800 hover:text-blue-500">Reservation</a>
                <a href="javascript:void(0);" onclick="logout()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </div>
</nav>
<script>
function logout() {
    window.location.href = 'logout.php';
}
</script>