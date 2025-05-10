<?php
    include("../connection/history_process.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History</title>
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <!-- Tailwind CSS -->
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .history-table th {
            position: relative;
        }
        
        .history-table th::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            transform: scaleX(0);
            background: linear-gradient(90deg, #3182ce, #7f9cf5);
            transition: transform 0.3s ease;
        }
        
        .history-table th:hover::after {
            transform: scaleX(1);
        }
        
        .feedback-btn {
            position: relative;
            overflow: hidden;
        }
        
        .feedback-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.2);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
            z-index: 1;
        }
        
        .feedback-btn:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        
        .feedback-btn i,
        .feedback-btn span {
            position: relative;
            z-index: 2;
        }
        
        /* Custom scrollbar for table container */
        .table-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Tab styling */
        .tab-btn {
            position: relative;
        }
        
        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }
        
        .tab-btn.active {
            color: #2563eb;
        }
        
        .tab-btn.active::after {
            background-color: #2563eb;
            transform: scaleX(1);
            transform-origin: left;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Include navbar -->
    <header class="w-full">
        <?php include 'navbar.php'; ?>
    </header>

    <div class="container mx-auto px-4 py-6">
        <!-- Header with search bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Session History</h1>
                <p class="text-sm text-gray-500 mt-1">View your previous lab sessions and reservations</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search input -->
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by lab or purpose..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64 transition-all duration-200" />
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <!-- Filter dropdown -->
                <div class="relative">
                    <select id="filterSelect" class="appearance-none bg-white pl-4 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="semester">This Semester</option>
                    </select>
                    <div class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6">
            <div class="flex border-b border-gray-200">
                <button id="tabSitIn" class="tab-btn px-6 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 focus:outline-none active">
                    <i class="fas fa-desktop mr-2"></i> Direct Sit-in History
                </button>
                <button id="tabReservations" class="tab-btn px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                    <i class="fas fa-calendar-alt mr-2"></i> Reservations
                </button>
            </div>
        </div>
        
        <!-- Tab Contents -->
        <div id="tabContentSitIn" class="tab-content">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 transform transition-transform hover:scale-[1.02] duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Total Sessions</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $stats['total_sessions']; ?></p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fas fa-desktop"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-chart-line mr-1"></i> Session history
                        </span>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 transform transition-transform hover:scale-[1.02] duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Total Hours</p>
                            <p class="text-xl font-bold text-gray-800"><?php echo $totalHours; ?> hrs <?php echo $remainingMinutes; ?> min</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-calendar-alt mr-1"></i> All time usage
                        </span>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 transform transition-transform hover:scale-[1.02] duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Rated Sessions</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $stats['rated_sessions']; ?><span class="text-sm font-normal text-gray-500 ml-1">of <?php echo $stats['completed_sessions']; ?></span></p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-comment-alt mr-1"></i> Feedback provided
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Main table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold text-gray-700">Recent Sessions</h2>
                    <?php if ($historyResult->num_rows > 0): ?>
                    <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        Showing <?php echo min($historyResult->num_rows, 10); ?> of <?php echo $historyResult->num_rows; ?> entries
                    </span>
                    <?php endif; ?>
                </div>
                
                <?php if ($historyResult->num_rows > 0): ?>
                <div class="table-container overflow-x-auto">
                    <table class="w-full history-table">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3 font-medium">Laboratory</th>
                                <th class="px-6 py-3 font-medium">Purpose</th>
                                <th class="px-6 py-3 font-medium">Login Time</th>
                                <th class="px-6 py-3 font-medium">Logout Time</th>
                                <th class="px-6 py-3 font-medium">Duration</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium">Rating</th>
                                <th class="px-6 py-3 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php while ($row = $historyResult->fetch_assoc()): 
                                // Calculate duration
                                $duration = "N/A";
                                if ($row['check_out_time']) {
                                    $checkIn = new DateTime($row['check_in_time']);
                                    $checkOut = new DateTime($row['check_out_time']);
                                    $interval = $checkIn->diff($checkOut);
                                    $hours = $interval->h + ($interval->days * 24);
                                    $minutes = $interval->i;
                                    $duration = $hours . "h " . $minutes . "m";
                                }
                                
                                // Determine status class
                                $statusClass = $row['status'] == 'active' ? 
                                    'bg-green-50 text-green-700' : 
                                    'bg-gray-50 text-gray-700';
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                            <?php echo htmlspecialchars($row['laboratory']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($row['purpose']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y g:i A', strtotime($row['check_in_time'])); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo $row['check_out_time'] ? date('M d, Y g:i A', strtotime($row['check_out_time'])) : '<span class="text-green-500">Still Active</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $duration; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-medium px-2 py-1 rounded-full <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($row['status'] == 'completed'): ?>
                                            <?php if ($row['rating']): ?>
                                                <div class="flex">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= $row['rating'] ? 'text-yellow-500' : 'text-gray-300'; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">
                                                    Not Rated
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($row['status'] == 'completed'): ?>
                                            <button onclick="openRatingModal(
                                                        <?php echo $row['sit_in_id']; ?>, 
                                                        '<?php echo htmlspecialchars($row['purpose']); ?>', 
                                                        '<?php echo htmlspecialchars($row['laboratory']); ?>',
                                                        <?php echo $row['rating'] ? $row['rating'] : 0; ?>,
                                                        '<?php echo htmlspecialchars($row['feedback'] ?? ''); ?>'
                                                    )" 
                                                class="feedback-btn bg-indigo-600 text-white text-xs px-3 py-1.5 rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                                <i class="fas fa-comment-alt mr-1"></i>
                                                <span><?php echo $row['rating'] ? 'Edit Feedback' : 'Add Feedback'; ?></span>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">--</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-medium">1</span> to <span class="font-medium"><?php echo min($historyResult->num_rows, 10); ?></span> of <span class="font-medium"><?php echo $historyResult->num_rows; ?></span> results
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <button class="px-3 py-1 border rounded-md bg-blue-50 text-blue-600 font-medium">1</button>
                        
                        <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <?php else: ?>
                <div class="py-8 text-center">
                    <div class="inline-flex rounded-full bg-gray-100 p-4">
                        <div class="rounded-full stroke-gray-500 bg-gray-200 p-4">
                            <svg class="w-8 h-8" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2C7.373 2 2 7.373 2 14C2 20.627 7.373 26 14 26C20.627 26 26 20.627 26 14C26 7.373 20.627 2 14 2ZM14 24C8.486 24 4 19.514 4 14C4 8.486 8.486 4 14 4C19.514 4 24 8.486 24 14C24 19.514 19.514 24 14 24Z" fill="#A1A1AA"/>
                                <path d="M14 10C14.5523 10 15 9.55228 15 9C15 8.44772 14.5523 8 14 8C13.4477 8 13 8.44772 13 9C13 9.55228 13.4477 10 14 10Z" fill="#A1A1AA"/>
                                <path d="M13 12C13 11.4477 13.4477 11 14 11C14.5523 11 15 11.4477 15 12V19C15 19.5523 14.5523 20 14 20C13.4477 20 13 19.5523 13 19V12Z" fill="#A1A1AA"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h2 class="mt-4 text-lg font-medium text-gray-900">No history found</h2>
                    <p class="mt-2 text-sm text-gray-500">You haven't had any sit-in sessions yet.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Reservations Tab Content -->
        <div id="tabContentReservations" class="tab-content hidden">
            <!-- Stats Cards for Reservations -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 transform transition-transform hover:scale-[1.02] duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Total Reservations</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $reservationStats['total_reservations']; ?></p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-history mr-1"></i> All time reservations
                        </span>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 transform transition-transform hover:scale-[1.02] duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Approved</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $reservationStats['approved_reservations']; ?><span class="text-sm font-normal text-gray-500 ml-1">of <?php echo $reservationStats['total_reservations']; ?></span></p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-thumbs-up mr-1"></i> Approved requests
                        </span>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 transform transition-transform hover:scale-[1.02] duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Completed</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $reservationStats['completed_reservations']; ?><span class="text-sm font-normal text-gray-500 ml-1">of <?php echo $reservationStats['approved_reservations']; ?> approved</span></p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-flag-checkered mr-1"></i> Completed reservations
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Reservations Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold text-gray-700">My Reservations</h2>
                    <?php if ($reservationResult->num_rows > 0): ?>
                    <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        Showing <?php echo min($reservationResult->num_rows, 10); ?> of <?php echo $reservationResult->num_rows; ?> entries
                    </span>
                    <?php endif; ?>
                </div>
                
                <?php if ($reservationResult->num_rows > 0): ?>
                <div class="table-container overflow-x-auto">
                    <table class="w-full history-table">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3 font-medium">Lab Room</th>
                                <th class="px-6 py-3 font-medium">PC</th>
                                <th class="px-6 py-3 font-medium">Purpose</th>
                                <th class="px-6 py-3 font-medium">Date</th>
                                <th class="px-6 py-3 font-medium">Time</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php while ($row = $reservationResult->fetch_assoc()): 
                                // Determine status class based on reservation status
                                switch($row['status']) {
                                    case 'approved':
                                        $statusClass = 'bg-green-50 text-green-700';
                                        break;
                                    case 'pending':
                                        $statusClass = 'bg-yellow-50 text-yellow-700';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-gray-50 text-gray-700';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-blue-50 text-blue-700';
                                        break;
                                    case 'disapproved':
                                        $statusClass = 'bg-red-50 text-red-700';
                                        break;
                                    default:
                                        $statusClass = 'bg-gray-50 text-gray-700';
                                }

                                // Format the reservation time
                                $time = date('g:i A', strtotime($row['time_in'])) . ' - ' . 
                                         ($row['time_out'] ? date('g:i A', strtotime($row['time_out'])) : 'N/A');
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                            <?php echo htmlspecialchars($row['lab_room']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">PC <?php echo htmlspecialchars($row['pc_number']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($row['purpose']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($row['reservation_date'])); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $time; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-medium px-2 py-1 rounded-full <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-medium">1</span> to <span class="font-medium"><?php echo min($reservationResult->num_rows, 10); ?></span> of <span class="font-medium"><?php echo $reservationResult->num_rows; ?></span> results
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <button class="px-3 py-1 border rounded-md bg-blue-50 text-blue-600 font-medium">1</button>
                        
                        <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <?php else: ?>
                <div class="py-8 text-center">
                    <div class="inline-flex rounded-full bg-gray-100 p-4">
                        <div class="rounded-full stroke-gray-500 bg-gray-200 p-4">
                            <svg class="w-8 h-8" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2C7.373 2 2 7.373 2 14C2 20.627 7.373 26 14 26C20.627 26 26 20.627 26 14C26 7.373 20.627 2 14 2ZM14 24C8.486 24 4 19.514 4 14C4 8.486 8.486 4 14 4C19.514 4 24 8.486 24 14C24 19.514 19.514 24 14 24Z" fill="#A1A1AA"/>
                                <path d="M14 10C14.5523 10 15 9.55228 15 9C15 8.44772 14.5523 8 14 8C13.4477 8 13 8.44772 13 9C13 9.55228 13.4477 10 14 10Z" fill="#A1A1AA"/>
                                <path d="M13 12C13 11.4477 13.4477 11 14 11C14.5523 11 15 11.4477 15 12V19C15 19.5523 14.5523 20 14 20C13.4477 20 13 19.5523 13 19V12Z" fill="#A1A1AA"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h2 class="mt-4 text-lg font-medium text-gray-900">No reservations found</h2>
                    <p class="mt-2 text-sm text-gray-500">You haven't made any reservations yet.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Feedback Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800">Session Feedback</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="ratingForm" method="POST" action="">
                <div class="p-5">
                    <input type="hidden" id="modal-sit-in-id" name="sit_in_id">
                    <input type="hidden" id="rating" name="rating" value="0">
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 mb-1"><span class="font-medium">Purpose:</span> <span id="modal-purpose"></span></p>
                        <p class="text-sm text-gray-700"><span class="font-medium">Laboratory:</span> <span id="modal-lab"></span></p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">How would you rate this session?</p>
                        <div class="flex space-x-2 text-2xl">
                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors duration-200" data-rating="1">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors duration-200" data-rating="2">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors duration-200" data-rating="3">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors duration-200" data-rating="4">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors duration-200" data-rating="5">
                                <i class="fas fa-star"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="feedback" class="block text-sm text-gray-600 mb-2">Comments (Optional)</label>
                        <textarea id="feedback" name="feedback" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="4" placeholder="Share your experience with this lab session..."></textarea>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                    <button type="button" id="cancelFeedback" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" name="submit_rating" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
    <script>
        Swal.fire({
            icon: '<?php echo $_SESSION['msg_type']; ?>',
            title: '<?php echo $_SESSION['message']; ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    <?php
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
    ?>
    <?php endif; ?>

    <script>
        // Wait for the DOM to be fully loaded before attaching event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabSitIn = document.getElementById('tabSitIn');
            const tabReservations = document.getElementById('tabReservations');
            const tabContentSitIn = document.getElementById('tabContentSitIn');
            const tabContentReservations = document.getElementById('tabContentReservations');
            
            tabSitIn.addEventListener('click', function() {
                // Update tab buttons
                tabSitIn.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'active');
                tabSitIn.classList.remove('text-gray-500');
                tabReservations.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'active');
                tabReservations.classList.add('text-gray-500');
                
                // Update tab content
                tabContentSitIn.classList.remove('hidden');
                tabContentReservations.classList.add('hidden');
            });
            
            tabReservations.addEventListener('click', function() {
                // Update tab buttons
                tabReservations.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'active');
                tabReservations.classList.remove('text-gray-500');
                tabSitIn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'active');
                tabSitIn.classList.add('text-gray-500');
                
                // Update tab content
                tabContentReservations.classList.remove('hidden');
                tabContentSitIn.classList.add('hidden');
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const activeTab = document.querySelector('.tab-content:not(.hidden)');
                    const tableRows = activeTab.querySelectorAll('tbody tr');
                    
                    tableRows.forEach(row => {
                        const textContent = row.textContent.toLowerCase();
                        if (textContent.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
            
            // Filter select
            const filterSelect = document.getElementById('filterSelect');
            if (filterSelect) {
                filterSelect.addEventListener('change', function() {
                    const option = this.value;
                    const activeTab = document.querySelector('.tab-content:not(.hidden)');
                    const tableRows = activeTab.querySelectorAll('tbody tr');
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    
                    tableRows.forEach(row => {
                        const dateCell = row.querySelector('td:nth-child(3)').textContent;
                        const date = new Date(dateCell);
                        
                        if (option === 'all') {
                            row.style.display = '';
                        } else if (option === 'today' && date >= today) {
                            row.style.display = '';
                        } else if (option === 'week' && date >= weekStart) {
                            row.style.display = '';
                        } else if (option === 'month' && date >= monthStart) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
            
            // Rating modal functionality
            const ratingModal = document.getElementById('ratingModal');
            const modalContent = document.getElementById('modalContent');
            const closeModal = document.getElementById('closeModal');
            const cancelFeedback = document.getElementById('cancelFeedback');
            const starButtons = document.querySelectorAll('.star-btn');
            
            // Initialize star buttons
            starButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    document.getElementById('rating').value = rating;
                    
                    // Update star visual state
                    starButtons.forEach((btn, index) => {
                        if (index < rating) {
                            btn.classList.add('text-yellow-500');
                            btn.classList.remove('text-gray-300');
                        } else {
                            btn.classList.remove('text-yellow-500');
                            btn.classList.add('text-gray-300');
                        }
                    });
                });
            });
            
            // Close modal handlers
            if (closeModal && cancelFeedback) {
                const hideModal = () => {
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        ratingModal.classList.add('hidden');
                    }, 300);
                };
                
                closeModal.addEventListener('click', hideModal);
                cancelFeedback.addEventListener('click', hideModal);
                
                ratingModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideModal();
                    }
                });
            }

            // Show disapproval reason function (kept for disapproved reservations)
            window.showDisapprovalReason = function(reason) {
                Swal.fire({
                    title: 'Reservation Disapproved',
                    text: reason,
                    icon: 'info',
                    confirmButtonColor: '#3085d6'
                });
            };
        });
        
        function openRatingModal(sitInId, purpose, laboratory, rating, feedback) {
            const modal = document.getElementById('ratingModal');
            const modalContent = document.getElementById('modalContent');
            
            document.getElementById('modal-sit-in-id').value = sitInId;
            document.getElementById('modal-purpose').textContent = purpose;
            document.getElementById('modal-lab').textContent = laboratory;
            document.getElementById('feedback').value = feedback || '';
            
            // Set star ratings
            const starButtons = document.querySelectorAll('.star-btn');
            starButtons.forEach((button, index) => {
                if (index < rating) {
                    button.classList.add('text-yellow-500');
                    button.classList.remove('text-gray-300');
                } else {
                    button.classList.remove('text-yellow-500');
                    button.classList.add('text-gray-300');
                }
            });
            
            document.getElementById('rating').value = rating;
            
            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    </script>
</body>
</html>