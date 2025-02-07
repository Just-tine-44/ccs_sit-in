<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center my-8">
            <h1 class="text-4xl font-bold text-gray-800">Department of the Computer Studies</h1>
            <a href="javascript:void(0);" onclick="logout()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Logout</a>
        </header>
        <main class="text-center">
            <p class="text-lg text-gray-600">Welcome Students to CCS College</p>
            <a href="#" class="mt-4 inline-block px-6 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Get Started</a>
        </main>
    </div>
</body>
</html>