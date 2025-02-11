<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="script.js"></script>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
<nav class="bg-white shadow mb-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="#" class="text-gray-800 font-bold text-xl hover:text-blue-500">Dashboard</a>
            <div class="flex space-x-4 items-center">
                <a href="#" class="text-gray-800 hover:text-blue-500">Notifications</a>
                <a href="#" class="text-gray-800 hover:text-blue-500">Home</a>
                <a href="#" class="text-gray-800 hover:text-blue-500">Edit Profile</a>
                <a href="#" class="text-gray-800 hover:text-blue-500">History</a>
                <a href="#" class="text-gray-800 hover:text-blue-500">Reservation</a>
                <a href="javascript:void(0);" onclick="logout()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </div>
</nav>
<div class="container mx-auto p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2 text-center">Student Information</h2>
        <div class="border-b-2 border-black pb-4 mb-4">
            <img src="images/person.jpg" alt="avatar" class="w-32 h-32 mx-auto rounded-full">
        </div>
        <p class="text-gray-700 mb-2"><i class="fas fa-user"></i> Name: John Doe</p>
        <p class="text-gray-700 mb-2"><i class="fas fa-book"></i> Course: Computer Science</p>
        <p class="text-gray-700 mb-2"><i class="fas fa-calendar"></i> Year: 123456</p>
        <p class="text-gray-700 mb-2"><i class="fas fa-envelope"></i> Email:</p>
        <p class="text-gray-700 mb-2"><i class="fas fa-home"></i> Address:</p>
        <p class="text-gray-700 mb-2"><i class="fas fa-clock"></i> Session:</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2">Announcements</h2>
        <p class="text-gray-700">No new announcements.</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2">Rules and Regulations</h2>
        <p class="text-gray-700">1. Attend all classes.</p>
        <p class="text-gray-700">2. Submit assignments on time.</p>
        <p class="text-gray-700">3. Maintain discipline.</p>
    </div>
</div>
</body>
</html>


<!-- <div class="container mx-auto p-4">
        <header class="flex justify-between items-center my-8">
            <h1 class="text-4xl font-bold text-gray-800">Department of the Computer Studies</h1>
            <a href="javascript:void(0);" onclick="logout()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Logout</a>
        </header>
        <main class="text-center">
            <p class="text-lg text-gray-600">Welcome Students to CCS College</p>
            <a href="#" class="mt-4 inline-block px-6 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Get Started</a>
        </main>
    </div> -->