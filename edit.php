<?php 
    include("connection/profile_edit.php");

$stud_session = isset($_SESSION['stud_session']) ? $_SESSION['stud_session'] : ['session' => 'N/A'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="icon" type="image/png" href="images/wbccs.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    <div class="container mx-auto p-6 max-w-6xl">
        <div class="bg-white p-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-8 text-center">Edit Profile</h2>
            
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <div class="flex flex-col md:flex-row">
                    <!-- Left: Profile Picture -->
                    <div class="md:w-1/3 flex flex-col items-center justify-center md:justify-center h-full pt-8 md:pt-6">
                    <div class="profile-pic-container mb-4 flex justify-center relative group">
                        <img id="preview-image" 
                            src="<?php echo $user['profileImg'] ?? 'images/person.jpg'; ?>" 
                            alt="Profile Picture" 
                            class="w-40 h-40 rounded-full object-cover transition duration-300 ease-in-out">
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 ease-in-out">
                            <span class="text-white font-medium text-lg">Your profile</span>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <label for="profile_picture" class="cursor-pointer inline-block bg-blue-50 text-blue-700 px-6 py-2 rounded-full hover:bg-blue-100 text-center">
                            Choose New Photo
                        </label>
                        <input type="file" 
                            name="profile_picture" 
                            id="profile_picture" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewImage(this);">
                    </div>
                    <div class="flex justify-center mt-5">
                        <p class="text-gray-700 mb-2 border border-black rounded-full px-4 py-2"><i class="fas fa-clock"></i> Session: <?php echo $stud_session['session']; ?></p>
                    </div>
                </div>

                    <!-- Right: Form Fields -->
                    <div class="md:w-2/3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">ID Number:</label>
                                <input type="text" name="idno" value="<?php echo $user['idno']; ?>" class="w-full border px-4 py-2 rounded-full bg-gray-100 border-gray-200" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Course:</label>
                                <input type="text" name="course" value="<?php echo $user['course']; ?>" class="w-full border px-4 py-2 rounded-full bg-gray-100 border-gray-200" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Lastname:</label>
                                <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" class="w-full border px-4 py-2 rounded-full border-gray-200" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Year Level:</label>
                                <input type="text" name="level" value="<?php echo $user['level']; ?>" class="w-full border px-4 py-2 rounded-full bg-gray-100 border-gray-200" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Firstname:</label>
                                <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" class="w-full border px-4 py-2 rounded-full border-gray-200" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email:</label>
                                <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full border px-4 py-2 rounded-full border-gray-200" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Middlename:</label>
                                <input type="text" name="midname" value="<?php echo $user['midname']; ?>" class="w-full border px-4 py-2 rounded-full border-gray-200" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Address:</label>
                                <input type="text" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" class="w-full border px-4 py-2 rounded-full border-gray-200" required>
                            </div>
                            <div class="col-span-2 mt-4 flex justify-end">
                                <a href="password_edit.php" class="inline-flex items-center px-4 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-full hover:bg-gray-300 transition duration-200">
                                    <i class="fas fa-lock mr-1"></i> Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Save Button - Centered -->
                <div class="flex justify-center mt-8">
                    <button type="submit" name="update" class="px-8 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Save Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>