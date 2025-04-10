<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include('conn/dbcon.php');

// Get selected lab room from URL parameter
$selected_lab = isset($_GET['lab']) ? $_GET['lab'] : null;

// Get list of all lab rooms
$lab_rooms = ['524', '526', '528', '530', '542', '544', '517'];

// If a lab room is selected, fetch its schedule
$schedule = null;
if ($selected_lab) {
    $sql = "SELECT * FROM lab_schedules WHERE lab_room = ? AND is_active = 1 ORDER BY upload_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_lab);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $schedule = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Schedules | CCS Lab System</title>
    <link rel="icon" type="image/png" href="images/wbccs.png">
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fc;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.2); }
            50% { box-shadow: 0 0 20px 0 rgba(79, 70, 229, 0.3); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-delay {
            opacity: 0;
        }
        .glow-on-hover:hover {
            animation: pulseGlow 2s infinite;
        }
        .lab-card-shadow {
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.7);
        }
        .card-hover-effect {
            transition: all 0.3s ease;
        }
        .card-hover-effect:hover {
            transform: translateY(-5px);
        }
        .schedule-container {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include "navbar.php"; ?>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-8 pb-16 max-w-7xl">
        <!-- Page Header -->
        <div class="text-center mb-8 animate-fade-in-up">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-2">
                <i class="fas fa-calendar-alt mr-2"></i> Lab Schedule Directory
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Laboratory Room Schedules</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Access up-to-date schedules for all computer laboratory rooms
            </p>
        </div>
    
        <!-- Selected Lab Summary (if any) -->
        <?php if ($selected_lab): ?>
            <div class="mb-10 animate-fade-in-up">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="lab_schedule.php" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600">
                                <i class="fas fa-home mr-2"></i>
                                All Labs
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                                <span class="text-sm font-medium text-blue-600">Lab Room <?= $selected_lab ?></span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-door-open mr-3"></i>
                                Lab Room <?= $selected_lab ?>
                            </h2>
                            <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i> Room <?= $selected_lab ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    
        <!-- Lab Rooms Grid -->
        <div class="mb-16">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Laboratory Rooms</h2>
                    <p class="mt-1 text-gray-600">Select a lab room to view its current schedule</p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <div class="inline-flex items-center px-4 py-2 rounded-lg bg-white text-sm font-medium text-gray-700 border border-gray-200 shadow-sm">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span><?= count($lab_rooms) ?> Rooms Available</span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-5">
                <?php foreach($lab_rooms as $index => $room): ?>
                    <a href="?lab=<?= $room ?>" 
                       class="group opacity-0 animate-delay relative overflow-hidden rounded-2xl lab-card-shadow border <?= ($selected_lab == $room) ? 'border-blue-300 ring-2 ring-blue-500' : 'border-gray-100 hover:border-blue-200' ?> transition-all duration-300 transform hover:-translate-y-1 glow-on-hover bg-white card-hover-effect"
                       style="animation: fadeInUp <?= 0.2 + ($index * 0.05) ?>s ease-out forwards;">
                        <div class="h-32 bg-gradient-to-br <?= ($selected_lab == $room) ? 'from-blue-500 to-indigo-600' : 'from-blue-400 to-indigo-500' ?> flex items-center justify-center transition-all duration-500 group-hover:from-blue-500 group-hover:to-indigo-600">
                            <div class="absolute top-2 right-2 z-10">
                                <span class="flex h-6 items-center justify-center rounded-full bg-white bg-opacity-90 px-2.5 text-xs font-medium text-gray-700 shadow-sm">
                                    <i class="fas fa-door-open mr-1"></i> Room
                                </span>
                            </div>
                            
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <span class="text-white text-4xl font-bold relative z-10 group-hover:scale-110 transition-transform duration-300"><?= $room ?></span>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 text-center">Lab Room <?= $room ?></h3>
                            <div class="flex items-center justify-center mt-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= ($selected_lab == $room) ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600 group-hover:bg-blue-50 group-hover:text-blue-600' ?> transition-colors duration-200">
                                    <i class="fas fa-calendar-alt mr-1.5"></i> View Schedule
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Schedule Display Section -->
        <?php if ($selected_lab): ?>
            <div class="animate-fade-in-up schedule-container">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 md:p-8">
                        <?php if ($schedule): ?>
                            <div class="flex flex-col lg:flex-row gap-8">
                                <div class="lg:w-1/3 flex flex-col">
                                    <div class="bg-gray-50 p-5 rounded-xl mb-4 border border-gray-100">
                                        <h3 class="font-semibold text-gray-800 text-lg mb-3 flex items-center">
                                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                            <?= htmlspecialchars($schedule['title']) ?>
                                        </h3>
                                        
                                        <?php if (!empty($schedule['description'])): ?>
                                            <div class="mt-4 text-gray-600 text-sm bg-white p-4 rounded-lg border border-gray-100">
                                                <p><?= htmlspecialchars($schedule['description']) ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-5 space-y-2 text-sm text-gray-500">
                                            <div class="flex items-center p-2 bg-white rounded-lg border border-gray-100">
                                                <i class="far fa-calendar text-blue-400 mr-3"></i>
                                                <span>Last updated: <span class="font-medium text-gray-700"><?= date('F d, Y', strtotime($schedule['upload_date'])) ?></span></span>
                                            </div>
                                            <div class="flex items-center p-2 bg-white rounded-lg border border-gray-100">
                                                <i class="far fa-user text-blue-400 mr-3"></i>
                                                <span>Uploaded by: <span class="font-medium text-gray-700"><?= htmlspecialchars($schedule['uploaded_by']) ?></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="uploads/schedules/<?= $schedule['schedule_image'] ?>" download class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white transition-colors duration-200 font-medium px-4 py-3 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-transform">
                                        <i class="fas fa-download mr-2"></i> Download Schedule
                                    </a>
                                </div>
                                
                                <div class="lg:w-2/3">
                                    <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                        <div class="relative group">
                                            <img 
                                                src="uploads/schedules/<?= $schedule['schedule_image'] ?>" 
                                                alt="<?= htmlspecialchars($schedule['title']) ?>"
                                                class="w-full h-auto max-h-[500px] object-contain p-2"
                                            >
                                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition-opacity duration-300 flex items-center justify-center">
                                                <button onclick="openFullscreen()" class="opacity-0 group-hover:opacity-100 transform scale-95 group-hover:scale-100 transition-all duration-300 bg-white text-blue-600 hover:bg-blue-50 font-medium px-5 py-2.5 rounded-lg shadow-lg">
                                                    <i class="fas fa-search-plus mr-2"></i> View Fullscreen
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center py-3 border-t border-gray-100">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center w-full" onclick="openFullscreen()">
                                                <i class="fas fa-expand-alt mr-2"></i> Click to enlarge
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="rounded-full bg-yellow-100 p-6 mb-5">
                                    <i class="fas fa-calendar-times text-yellow-500 text-4xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">No Schedule Assigned Yet</h3>
                                <p class="text-gray-600 max-w-lg">
                                    There's currently no schedule available for Lab Room <?= $selected_lab ?>. Please check back later or contact the lab administrator for more information.
                                </p>
                                <a href="lab_schedule.php" class="mt-8 inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                                    <i class="fas fa-arrow-left mr-2"></i> View All Lab Rooms
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Welcome Message when no room is selected -->
            <div class="animate-fade-in-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 schedule-container">
                    <div class="flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
                        <div class="rounded-full bg-blue-100 p-6 flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Select a Lab Room to View its Schedule</h3>
                            <p class="text-gray-600 max-w-2xl">
                                Click on any lab room card above to view its current schedule. The schedule displays class timings, 
                                availability for sit-ins, and any special arrangements for the current semester.
                            </p>
                            <div class="mt-6 flex flex-wrap gap-4">
                                <div class="inline-flex items-center bg-blue-50 px-3 py-2 rounded-lg text-sm text-blue-700">
                                    <i class="fas fa-clock mr-2"></i> Class Timings
                                </div>
                                <div class="inline-flex items-center bg-green-50 px-3 py-2 rounded-lg text-sm text-green-700">
                                    <i class="fas fa-user-check mr-2"></i> Availability for Sit-ins
                                </div>
                                <div class="inline-flex items-center bg-indigo-50 px-3 py-2 rounded-lg text-sm text-indigo-700">
                                    <i class="fas fa-calendar-alt mr-2"></i> Special Arrangements
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Fullscreen Modal -->
    <?php if (!empty($schedule)): ?>
    <div id="fullscreenModal" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden flex items-center justify-center backdrop-filter backdrop-blur-sm">
        <div class="absolute top-4 right-4 z-10">
            <button onclick="closeFullscreen()" class="text-white hover:text-blue-300 focus:outline-none bg-black bg-opacity-50 hover:bg-opacity-75 transition-colors duration-200 rounded-full p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="absolute top-4 left-4 text-white">
            <h3 class="text-xl font-medium flex items-center glass-effect text-gray-800 px-4 py-2 rounded-lg">
                <i class="fas fa-door-open text-blue-500 mr-2"></i>
                Lab Room <?= $selected_lab ?>: <?= htmlspecialchars($schedule['title']) ?>
            </h3>
        </div>
        <div class="absolute bottom-4 right-4">
            <a href="uploads/schedules/<?= $schedule['schedule_image'] ?>" download class="glass-effect text-gray-800 flex items-center px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-80 transition-colors duration-200">
                <i class="fas fa-download text-blue-500 mr-2"></i> Download Image
            </a>
        </div>
        <img 
            src="uploads/schedules/<?= $schedule['schedule_image'] ?>" 
            alt="<?= htmlspecialchars($schedule['title']) ?>"
            class="max-w-full max-h-screen p-4 object-contain"
        >
    </div>
    <?php endif; ?>
    
    <script>
        function openFullscreen() {
            document.getElementById('fullscreenModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeFullscreen() {
            document.getElementById('fullscreenModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal on escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFullscreen();
            }
        });
        
        // Initialize animations for elements with delay
        document.addEventListener('DOMContentLoaded', function() {
            // Get all elements with animate-delay class
            const delayedElements = document.querySelectorAll('.animate-delay');
            
            // Apply the animation with appropriate delay
            delayedElements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.animation = 'fadeInUp 0.6s ease-out forwards';
                }, 200 + (index * 100));
            });
        });
    </script>
</body>
</html>