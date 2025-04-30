<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
$profileImg = isset($user['profileImg']) && !empty($user['profileImg']) 
    ? (strpos($user['profileImg'], '../') === 0 ? $user['profileImg'] : '../' . $user['profileImg'])
    : '../images/person.jpg'; 
$stud_session = isset($_SESSION['stud_session']) ? $_SESSION['stud_session'] : ['session' => 'N/A'];
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['just_logged_in'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Welcome!',
            text: 'Hello <?php echo $user['firstname'] . ' ' . $user['midname'] . ' ' . $user['lastname'] . ' '?>👨‍🎓',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php unset($_SESSION['just_logged_in']); endif; ?>

<?php
include('../conn/dbcon.php'); // Adjust the path as necessary

// Fetch announcements from the database
$announcements = [];
$result = $conn->query("SELECT * FROM announcements ORDER BY post_date DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="../js/notifications.js"></script>
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
<?php include 'navbar.php'; ?>
<div class="container mx-auto p-4 grid grid-cols-1 md:grid-cols-6 lg:grid-cols-8 gap-4">
    <!-- Student Information Card (Smaller width) -->
    <div class="container mx-auto md:col-span-2 lg:col-span-2">
        <div class="bg-gradient-to-b from-blue-500 to-blue-600 p-3 text-white rounded-t-lg shadow-md">
            <div class="flex items-center justify-center">
                <div class="mr-2 bg-white bg-opacity-20 p-1 rounded-full">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h2 class="font-bold text-xl">Student Information</h2>
            </div>
            <div class="h-1 w-24 bg-white bg-opacity-30 rounded-full mx-auto mt-1"></div>
        </div>
        <div class="bg-white p-6 rounded-b-lg shadow">
            <div class="pb-4 mb-4 flex justify-center">
                <div class="relative">
                    <!-- Profile Picture with Glow Effect -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 via-blue-500 to-indigo-500 rounded-full opacity-75 blur-sm animate-pulse"></div>
                    <div class="relative rounded-full overflow-hidden border-4 border-white shadow-lg h-32 w-32">
                        <img 
                            src="<?php echo $profileImg; ?>" 
                            alt="Profile Pic" 
                            class="h-full w-full object-cover transition-transform duration-300 hover:scale-110"
                        >
                    </div>
                    
                    <!-- Status Indicator -->
                    <div class="absolute bottom-0 right-1">
                        <div class="flex items-center justify-center">
                            <div class="h-5 w-5 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <!-- Name -->
                <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Full Name</p>
                        <p class="font-medium text-gray-800"><?php echo $user['firstname'] . ' ' . $user['midname'] . ' ' . $user['lastname']; ?></p>
                    </div>
                </div>

                <!-- Course -->
                <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-book text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Program</p>
                        <p class="font-medium text-gray-800"><?php echo $user['course']; ?></p>
                    </div>
                </div>

                <!-- Year Level -->
                <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-calendar text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Year Level</p>
                        <p class="font-medium text-gray-800"><?php echo $user['level']; ?></p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-envelope text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Email Address</p>
                        <p class="font-medium text-gray-800 text-sm break-all"><?php echo $user['email']; ?></p>
                    </div>
                </div>

                <!-- Address -->
                <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-home text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Home Address</p>
                        <p class="font-medium text-gray-800 text-sm"><?php echo $user['address']; ?></p>
                    </div>
                </div>

                <!-- Sessions -->
                <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-blue-500"></i>
                    </div>
                    <div class="w-full">
                        <div class="flex justify-between items-center">
                            <p class="text-xs text-gray-500 mb-0.5">Remaining Sessions</p>
                            <p class="text-xs font-medium text-blue-600"><?php echo $stud_session['session']; ?><span class="text-gray-400">/30</span></p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <?php
                                $session = intval($stud_session['session']);
                                $percentage = min(100, ($session / 30) * 100);
                                
                                if ($percentage > 60) {
                                    $color = "bg-green-500";
                                } else if ($percentage > 30) {
                                    $color = "bg-yellow-500";
                                } else {
                                    $color = "bg-red-500";
                                }
                            ?>
                            <div class="<?php echo $color; ?> h-1.5 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Card (Slightly Bigger) -->
    <div class="container mx-auto md:col-span-2 lg:col-span-3">
        <div class="bg-gradient-to-b from-blue-500 to-blue-600 p-3 text-white rounded-t-lg shadow-md">
            <div class="flex items-center justify-center">
                <div class="mr-2 bg-white bg-opacity-20 p-1 rounded-full">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h2 class="font-bold text-xl">Announcements</h2>
            </div>
            <div class="h-1 w-24 bg-white bg-opacity-30 rounded-full mx-auto mt-1"></div>
        </div>
        <div class="bg-white p-5 rounded-b-lg shadow h-[550px] overflow-y-auto custom-scrollbar" style="max-height: 539px;">
            <?php if (empty($announcements)): ?>
        <div class="flex flex-col items-center justify-center h-full py-10 text-center">
            <div class="bg-blue-50 rounded-full p-5 mb-4">
                <i class="fas fa-bell-slash text-blue-300 text-4xl"></i>
            </div>
            <p class="text-gray-500 font-medium">No new announcements</p>
            <p class="text-gray-400 text-sm mt-2">Check back later for updates</p>
        </div>
            <?php else: ?>
                <?php foreach ($announcements as $announcement): ?>
                    <div class="bg-white p-5 rounded-xl shadow-sm mb-4 border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full p-2 mr-3">
                                    <i class="fas fa-bullhorn text-blue-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">
                                        <?php echo htmlspecialchars($announcement['admin_name']); ?>
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                        <i class="far fa-clock mr-1"></i>
                                        <?php echo date('M d, Y - h:i A', strtotime($announcement['post_date'])); ?>
                                    </div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($announcement['category'] ?? 'Announcement'); ?>
                            </span>
                        </div>
                        
                        <div class="pl-12 border-l-2 border-blue-100 ml-2">
                            <div class="text-gray-700 leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($announcement['message'])); ?>
                            </div>
                            
                            <?php if (!empty($announcement['image'])): ?>
                                <div class="mt-3 rounded-lg overflow-hidden">
                                    <img src="<?php echo htmlspecialchars($announcement['image']); ?>" alt="Announcement image" class="w-full h-auto rounded-lg hover:opacity-95 transition-opacity">
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($announcement['file'])): ?>
                                <a href="<?php echo htmlspecialchars($announcement['file']); ?>" class="flex items-center mt-3 text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-paperclip mr-2"></i>
                                    <span>Attachment</span>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex justify-end mt-4">
                            <span class="text-xs text-gray-400"><?php echo htmlspecialchars($announcement['department'] ?? 'College of Information & Computer Studies'); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>



    <!-- Rules and Regulation Card (Reduced width slightly) -->
    <div class="container mx-auto md:col-span-4 lg:col-span-3">
        <div class="bg-gradient-to-b from-blue-500 to-blue-600 p-3 text-white rounded-t-lg shadow-md">
            <div class="flex items-center justify-center">
                <div class="mr-2 bg-white bg-opacity-20 p-1 rounded-full">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h2 class="font-bold text-xl">Rules and Regulation</h2>
            </div>
            <div class="h-1 w-24 bg-white bg-opacity-30 rounded-full mx-auto mt-1"></div>
        </div>
        <div class="bg-white p-5 rounded-b-lg shadow h-[550px] overflow-y-auto custom-scrollbar" style="max-height: 537px;">
        <div class="mb-8">
            <!-- University Logos -->
            <div class="flex justify-center items-center gap-8 mb-5">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full opacity-75 blur-sm group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white p-1 rounded-full">
                        <img src="../images/logoucs.jpg" alt="UC Logo" class="h-20 w-20 rounded-full object-cover shadow-md transform group-hover:scale-105 transition duration-300">
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full opacity-75 blur-sm group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white p-1 rounded-full">
                        <img src="../images/ccslogo.png" alt="CCS Logo" class="h-20 w-20 rounded-full object-cover shadow-md transform group-hover:scale-105 transition duration-300">
                    </div>
                </div>
            </div>
            
            <!-- University Title -->
            <div class="text-center space-y-1">
                <h2 class="text-xl font-bold text-gray-800 tracking-wide">
                    University of Cebu
                </h2>
                <div class="flex justify-center items-center">
                    <div class="h-0.5 w-12 bg-gradient-to-r from-transparent to-blue-500"></div>
                    <p class="px-3 font-semibold text-sm text-blue-700">COLLEGE OF INFORMATION & COMPUTER STUDIES</p>
                    <div class="h-0.5 w-12 bg-gradient-to-r from-blue-500 to-transparent"></div>
                </div>
            </div>
        </div>
            <div class="space-y-6">
            <!-- Header -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h3 class="text-xl font-bold text-blue-800 flex items-center">
                    <i class="fas fa-clipboard-list mr-3 text-blue-600"></i>
                    LABORATORY RULES AND REGULATIONS
                </h3>
                <p class="text-gray-700 mt-2 italic border-l-4 border-blue-300 pl-3">
                    To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:
                </p>
            </div>

            <!-- Rules Cards -->
            <div class="grid grid-cols-1 gap-4">
                <!-- Rule 1 -->
                <div class="bg-gray rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">1</div>
                        </div>
                        <p class="ml-4 text-gray-700">Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</p>
                    </div>
                </div>
                
                <!-- Rule 2 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">2</div>
                        </div>
                        <p class="ml-4 text-gray-700">Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</p>
                    </div>
                </div>
                
                <!-- Rule 3 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">3</div>
                        </div>
                        <p class="ml-4 text-gray-700">Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</p>
                    </div>
                </div>
                
                <!-- Rule 4 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">4</div>
                        </div>
                        <p class="ml-4 text-gray-700">Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</p>
                    </div>
                </div>
                
                <!-- Rule 5 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">5</div>
                        </div>
                        <p class="ml-4 text-gray-700">Deleting computer files and changing the set-up of the computer is a major offense.</p>
                    </div>
                </div>
                
                <!-- Rule 6 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">6</div>
                        </div>
                        <p class="ml-4 text-gray-700">Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</p>
                    </div>
                </div>
                
                <!-- Rule 7 with Sub-items -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">7</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-700 mb-2">Observe proper decorum while inside the laboratory:</p>
                            <ul class="text-gray-700 space-y-1.5 pl-5 list-none">
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-blue-500 text-xs mt-1.5 mr-2"></i>
                                    <span>Do not get inside the lab unless the instructor is present.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-blue-500 text-xs mt-1.5 mr-2"></i>
                                    <span>All bags, knapsacks, and the likes must be deposited at the counter.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-blue-500 text-xs mt-1.5 mr-2"></i>
                                    <span>Follow the seating arrangement of your instructor.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-blue-500 text-xs mt-1.5 mr-2"></i>
                                    <span>At the end of class, all software programs must be closed.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-blue-500 text-xs mt-1.5 mr-2"></i>
                                    <span>Return all chairs to their proper places after using.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Rule 8 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">8</div>
                        </div>
                        <p class="ml-4 text-gray-700">Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</p>
                    </div>
                </div>
                
                <!-- Rule 9 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">9</div>
                        </div>
                        <p class="ml-4 text-gray-700">Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</p>
                    </div>
                </div>
                
                <!-- Rule 10 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">10</div>
                        </div>
                        <p class="ml-4 text-gray-700">Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</p>
                    </div>
                </div>
                
                <!-- Rule 11 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">11</div>
                        </div>
                        <p class="ml-4 text-gray-700">For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</p>
                    </div>
                </div>
                
                <!-- Rule 12 -->
                <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">12</div>
                        </div>
                        <p class="ml-4 text-gray-700">Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</p>
                    </div>
                </div>
            </div>
            
            <!-- Disciplinary Section -->
            <div class="mt-8 bg-red-50 rounded-lg p-5">
                <h3 class="text-xl font-bold text-red-800 flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-600"></i>
                    DISCIPLINARY ACTION
                </h3>
                
                <div class="space-y-3">
                    <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="h-7 w-7 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold">
                                    <i class="fas fa-gavel text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">First Offense</p>
                                <p class="text-gray-700 mt-1">The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="h-7 w-7 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold">
                                    <i class="fas fa-exclamation-circle text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Second and Subsequent Offenses</p>
                                <p class="text-gray-700 mt-1">A recommendation for a heavier sanction will be endorsed to the Guidance Center.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</body>
</html>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px; /* Thin scrollbar */
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #e5e7eb; /* Light gray track */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #a0aec0; /* Default gray */
        border-radius: 10px;
        transition: background 0.3s ease-in-out;
    }

    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: linear-gradient(45deg, #3b82f6, #1d4ed8); /* Blue gradient */
    }
</style>