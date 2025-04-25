<?php include("./conn_back/reservation_process.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Management | Admin Dashboard</title>
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background-color: #f9fafb;
        }
        
        .card {
            transition: transform 0.2s;
            border-radius: 0.75rem;
        }
        
        .btn-transition {
            transition: all 0.2s;
        }

        .request-list {
            max-height: 530px; /* Set a fixed height for vertical scrolling */
            overflow-y: auto; /* Enable vertical scrollbar */
        }
        
        .pc-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 buttons per row instead of 5 */
            gap: 0.75rem; /* More space between buttons */
            max-height: 250px; /* Set a fixed height for vertical scrolling */
            overflow-y: auto; /* Enable vertical scrollbar */
            padding-right: 0.5rem; /* Add some padding for the scrollbar */
        }

        .pc-button {
            aspect-ratio: 1/1; /* Keep square shape */
            width: 50px; /* Set specific size */
            height: 50px; /* Set specific size */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem; /* More rounded corners */
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Add a subtle shadow */
        }
        
        .card-header {
            border-radius: 0.75rem 0.75rem 0 0;
        }
        
        .spinner {
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .tab-active {
            color: #1d4ed8;
            border-bottom: 2px solid #1d4ed8;
            background-color: #eff6ff;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-700">
    <?php include "navbar_admin.php"; ?>
    
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-server text-blue-600 mr-2"></i> Computer Lab Management
            </h1>
            
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-gray-600">
                    <?php echo date('F j, Y'); ?>
                </span>
                <button id="refreshBtn" class="bg-white p-2.5 rounded-lg text-gray-600 hover:text-blue-600 btn-transition border border-gray-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-sync-alt"></i>
                    <?php if (count($pending_requests) > 0): ?>
                        <span class="ml-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white"><?php echo count($pending_requests); ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
        
        <!-- Current Active Sessions -->
        <?php if (count($active_sessions) > 0): ?>
        <div class="mb-6">
            <div class="card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="card-header bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="font-semibold text-white text-lg flex items-center">
                        <i class="fas fa-users mr-2"></i> Current Sit-In Sessions
                        <span class="ml-auto bg-white text-blue-600 text-xs font-bold px-3 py-1 rounded-full"><?php echo count($active_sessions); ?></span>
                    </h2>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Number</th>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab Room</th>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PC</th>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($active_sessions as $session): 
                                $current_time = strtotime(date('H:i:s'));
                                $start_time = strtotime($session['start_time']);
                                $status = ($current_time >= $start_time) ? 'active' : 'upcoming';
                            ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800"><?php echo $session['student_name']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600"><?php echo $session['student_id']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">Room <?php echo $session['lab']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">PC <?php echo $session['pc']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                            $start_display = date('h:i A', strtotime($session['start_time']));
                                            $end_display = date('h:i A', strtotime($session['end_time']));
                                        ?>
                                        <div class="text-sm text-gray-600"><?php echo $start_display . ' - ' . $end_display; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-[200px] truncate" title="<?php echo $session['purpose']; ?>"><?php echo $session['purpose']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($status == 'active'): ?>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                                <span class="mr-1 h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800">
                                                <span class="mr-1 h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Starting Soon
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <!-- Computer Control Panel -->
            <div class="xl:col-span-3">
                <div class="card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="card-header bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="font-semibold text-white text-lg flex items-center">
                            <i class="fas fa-desktop mr-2"></i> Computer Control
                        </h2>
                    </div>
                    
                    <!-- Lab Room Selector -->
                    <div class="p-5 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Lab Room</label>
                        <div class="relative">
                            <select id="labRoomSelector" class="pl-4 pr-10 py-2.5 block w-full border border-gray-300 rounded-lg text-gray-700 appearance-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                                <?php foreach($available_labs as $lab): ?>
                                    <option value="<?php echo $lab; ?>" <?php echo ($lab == $selected_lab) ? 'selected' : ''; ?>>
                                        Room <?php echo $lab; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Computer Status Filter -->
                    <div class="p-5 border-b border-gray-100 bg-gray-50">
                        <div class="flex space-x-2 mb-3">
                            <button class="pc-filter flex-1 py-2 px-3 rounded-lg bg-blue-500 text-white text-sm font-medium border border-transparent hover:bg-blue-600 btn-transition" data-filter="all">
                                All <span class="ml-1 font-normal">(<?php echo $total_computers; ?>)</span>
                            </button>
                            <button class="pc-filter flex-1 py-2 px-3 rounded-lg bg-white text-gray-700 text-sm font-medium border border-gray-200 hover:bg-green-500 hover:text-white hover:border-transparent btn-transition" data-filter="available">
                                Available <span class="ml-1 font-normal">(<?php echo $available_count; ?>)</span>
                            </button>
                            <button class="pc-filter flex-1 py-2 px-3 rounded-lg bg-white text-gray-700 text-sm font-medium border border-gray-200 hover:bg-red-500 hover:text-white hover:border-transparent btn-transition" data-filter="used">
                                Used <span class="ml-1 font-normal">(<?php echo $used_count; ?>)</span>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" id="pcSearchInput" placeholder="Search PC..." class="pl-10 pr-4 py-2.5 block w-full border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Computer Grid -->
                    <div class="p-5">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Room <?php echo $selected_lab; ?> Computers</h3>
                        <div class="pc-grid max-h-[500px] overflow-y-auto pr-2 custom-scrollbar pb-2" id="pcGrid">
                            <?php foreach($computers as $pc_num => $pc): 
                                $status = $pc['status']; 
                                
                                if ($status == 'available') {
                                    $statusColor = 'bg-green-500';
                                    $hoverColor = 'hover:bg-green-600';
                                    $icon = 'fa-check-circle';
                                    $nextStatus = 'used';
                                    $tooltipText = 'Available - Click to mark as In Use';
                                } 
                                else if ($status == 'used') {
                                    $statusColor = 'bg-red-500';
                                    $hoverColor = 'hover:bg-red-600';
                                    $icon = 'fa-times-circle';
                                    $nextStatus = 'available';
                                    $tooltipText = 'Currently In Use - Click to mark as Available';
                                }
                                else if ($status == 'reserved') {
                                    $statusColor = 'bg-purple-500';
                                    $hoverColor = 'hover:bg-purple-600';
                                    $icon = 'fa-calendar-check';
                                    $nextStatus = 'available';
                                    $tooltipText = 'Reserved for Future Use - This PC is booked for an upcoming reservation';
                                }
                                else {
                                    $statusColor = 'bg-gray-400';
                                    $hoverColor = 'hover:bg-gray-500';
                                    $icon = 'fa-question-circle';
                                    $nextStatus = 'available';
                                    $tooltipText = 'Status Unknown - Click to mark as Available';
                                }
                            ?>
                            <div class="pc-item" data-status="<?php echo $status; ?>" data-pc-num="<?php echo $pc_num; ?>">
                                <button type="button" class="pc-button toggle-pc-btn <?php echo $statusColor . ' ' . $hoverColor; ?> text-white shadow-sm btn-transition relative group"
                                    data-pc-id="<?php echo $pc_num; ?>"
                                    data-new-status="<?php echo $nextStatus; ?>"
                                    data-lab-room="<?php echo $selected_lab; ?>"
                                    title="<?php echo $tooltipText; ?>">
                                    <i class="fas <?php echo $icon; ?> mb-1 text-lg"></i>
                                    <span class="text-xs font-medium">PC<?php echo $pc_num; ?></span>
                                    
                                    <!-- Enhanced Tooltip -->
                                    <div class="absolute opacity-0 group-hover:opacity-100 transition-opacity duration-300 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 z-50 pointer-events-none">
                                        <div class="bg-gray-900 text-white text-xs rounded py-1.5 px-2 shadow-lg">
                                            <p class="font-medium"><?php echo $tooltipText; ?></p>
                                            <?php if($status == 'reserved'): ?>
                                            <p class="text-purple-300 mt-1 text-[10px]">Auto-resets to Available after 30 minutes</p>
                                            <?php endif; ?>
                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Computer Status Legend -->
                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-600">
                        <div class="flex flex-wrap gap-x-6 gap-y-2">
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-green-500 rounded-full inline-block mr-1.5"></span>
                                <span class="font-medium">Available</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-red-500 rounded-full inline-block mr-1.5"></span>
                                <span class="font-medium">In Use</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-purple-500 rounded-full inline-block mr-1.5"></span>
                                <span class="font-medium">Reserved</span>
                                <span class="ml-1 text-[10px] text-gray-500">(Future Reservation)</span>
                            </div>
                        </div>
                        <p class="mt-2 text-[10px] text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Reserved PCs automatically return to Available status after 30 minutes
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Reservation Requests -->
            <div class="xl:col-span-5">
                <div class="card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="card-header bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="font-semibold text-white text-lg flex items-center">
                            <i class="fas fa-clock mr-2"></i> Pending Reservation Requests
                            <?php if (count($pending_requests) > 0): ?>
                                <span class="ml-auto bg-white text-blue-600 text-xs font-bold px-2.5 py-0.5 rounded-full"><?php echo count($pending_requests); ?></span>
                            <?php endif; ?>
                        </h2>
                    </div>
                    
                    <!-- Requests Filters -->
                    <div class="px-5 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3 bg-gray-50">
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" id="requestSearchInput" placeholder="Search requests..." class="pl-10 pr-4 py-2.5 block w-full border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <div class="relative">
                                <select id="requestLabFilter" class="py-2.5 pl-3 pr-10 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-sm appearance-none">
                                    <option value="">All Labs</option>
                                    <?php foreach($available_labs as $lab): ?>
                                        <option value="<?php echo $lab; ?>">Room <?php echo $lab; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="relative">
                                <select id="requestSortOrder" class="py-2.5 pl-3 pr-10 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-sm appearance-none">
                                    <option value="date_asc">Oldest First</option>
                                    <option value="date_desc">Newest First</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Requests List -->
                    <div class="request-list divide-y divide-gray-100 max-h-[600px] overflow-y-auto custom-scrollbar" id="requestsList">
                        <?php if (count($pending_requests) > 0): ?>
                            <?php foreach($pending_requests as $request): ?>
                                <div class="request-item p-5 hover:bg-gray-50 transition-colors" 
                                     data-lab="<?php echo $request['lab']; ?>"
                                     data-student-name="<?php echo strtolower($request['student_name']); ?>"
                                     data-student-id="<?php echo $request['student_id']; ?>"
                                     data-date="<?php echo strtotime($request['date']); ?>"
                                     data-purpose="<?php echo strtolower($request['purpose']); ?>">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-md font-semibold text-gray-800"><?php echo $request['student_name']; ?></h3>
                                        <span class="text-xs font-medium text-white px-2.5 py-1 rounded-full bg-blue-500">
                                            Pending
                                        </span>
                                    </div>
                                    
                                    <div class="mt-3 text-sm text-gray-600 grid grid-cols-2 gap-y-2 gap-x-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-id-card w-5 text-gray-500"></i>
                                            <span><?php echo $request['student_id']; ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar w-5 text-gray-500"></i>
                                            <span><?php echo date('M j, Y', strtotime($request['date'])); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock w-5 text-gray-500"></i>
                                            <span><?php echo date('h:i A', strtotime($request['time'])); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-door-open w-5 text-gray-500"></i>
                                            <span>Room <?php echo $request['lab']; ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-desktop w-5 text-gray-500"></i>
                                            <span>PC <?php echo $request['pc']; ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-tasks w-5 text-gray-500"></i>
                                            <span class="truncate" title="<?php echo $request['purpose']; ?>"><?php echo $request['purpose']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex space-x-3">
                                        <button 
                                            class="flex-1 py-2.5 px-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex items-center justify-center approve-btn btn-transition"
                                            data-request-id="<?php echo $request['id']; ?>"
                                        >
                                            <i class="fas fa-check mr-2"></i> Approve
                                        </button>
                                        <button 
                                            class="flex-1 py-2.5 px-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex items-center justify-center disapprove-btn btn-transition"
                                            data-request-id="<?php echo $request['id']; ?>"
                                        >
                                            <i class="fas fa-times mr-2"></i> Disapprove
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-10 text-center" id="noRequestsMessage">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-1">No Pending Requests</h3>
                                <p class="text-gray-500">All reservation requests have been processed.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Reservation Logs -->
            <div class="xl:col-span-4">
                <div class="card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="card-header bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="font-semibold text-white text-lg flex items-center">
                            <i class="fas fa-history mr-2"></i> Reservation Activity Logs
                            <?php if (count($reservation_logs) > 0): ?>
                                <span class="ml-auto bg-white text-blue-600 text-xs font-bold px-2.5 py-0.5 rounded-full"><?php echo count($reservation_logs); ?></span>
                            <?php endif; ?>
                        </h2>
                    </div>
                    
                    <!-- Log Filters -->
                    <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center gap-2">
                        <button class="log-filter py-2 px-3 rounded-lg bg-blue-500 text-white text-xs font-medium btn-transition" data-filter="all">
                            All
                        </button>
                        <button class="log-filter py-2 px-3 rounded-lg bg-white text-gray-700 text-xs font-medium border border-gray-200 btn-transition hover:bg-green-600 hover:text-white hover:border-transparent" data-filter="approved">
                            Approved
                        </button>
                        <button class="log-filter py-2 px-3 rounded-lg bg-white text-gray-700 text-xs font-medium border border-gray-200 btn-transition hover:bg-red-600 hover:text-white hover:border-transparent" data-filter="disapproved">
                            Disapproved
                        </button>
                        
                        <div class="relative mt-2 sm:mt-0 ml-0 sm:ml-auto w-full sm:w-auto">
                            <input type="text" id="logSearchInput" placeholder="Search logs..." class="pl-10 pr-4 py-2 block w-full border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-xs">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Logs List -->
                    <div class="request-list divide-y divide-gray-100 max-h-[600px] overflow-y-auto custom-scrollbar" id="logsList">
                        <?php if (count($reservation_logs) > 0): ?>
                            <?php foreach($reservation_logs as $log): 
                                $statusColor = ($log['status'] === 'approved') ? 'green' : 'red';
                                $statusIcon = ($log['status'] === 'approved') ? 'fa-check-circle' : 'fa-times-circle';
                            ?>
                                <div class="log-item p-4 hover:bg-gray-50 transition-colors" 
                                     data-status="<?php echo $log['status']; ?>"
                                     data-student-name="<?php echo strtolower($log['student_name']); ?>"
                                     data-student-id="<?php echo $log['student_id']; ?>">
                                    <div class="flex items-center">
                                        <div class="bg-<?php echo $statusColor; ?>-100 text-<?php echo $statusColor; ?>-600 rounded-full p-2 mr-3 flex-shrink-0">
                                            <i class="fas <?php echo $statusIcon; ?>"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center mb-1">
                                                <h4 class="text-sm font-medium text-gray-800 truncate"><?php echo $log['student_name']; ?></h4>
                                                <span class="text-xs text-gray-500 flex-shrink-0"><?php echo date('h:i A', strtotime($log['timestamp'])); ?></span>
                                            </div>
                                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                                                <?php if($log['status'] === 'approved'): ?>
                                                    Reservation for Room <?php echo $log['lab']; ?> (PC <?php echo $log['pc']; ?>) was approved by <?php echo $log['admin']; ?>.
                                                <?php else: ?>
                                                    Reservation was disapproved - <?php echo $log['reason']; ?>.
                                                <?php endif; ?>
                                            </p>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center rounded-full bg-<?php echo $statusColor; ?>-100 px-2 py-0.5 text-xs font-medium text-<?php echo $statusColor; ?>-800">
                                                    <?php echo ucfirst($log['status']); ?>
                                                </span>
                                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                                    <i class="fas fa-id-card mr-1 text-gray-400"></i>
                                                    <?php echo $log['student_id']; ?>
                                                </span>
                                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                                    <i class="fas fa-calendar-day mr-1 text-gray-400"></i>
                                                    <?php echo date('M j', strtotime($log['date'])); ?> at <?php echo date('h:i A', strtotime($log['time'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-10 text-center" id="noLogsMessage">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-600 mb-4">
                                    <i class="fas fa-history text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-1">No Activity Logs</h3>
                                <p class="text-gray-500">Activity logs will appear here when reservations are processed.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Redesigned Disapprove Modal -->
    <div id="disapproveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Disapprove Reservation</h3>
                <button id="closeDisapproveBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="disapproveForm" class="pb-2">
                <input type="hidden" name="disapprove_request" value="1">
                <input type="hidden" name="request_id" id="disapprove_request_id">
                
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Disapproval</label>
                    <select name="reason" id="disapproval_reason" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                        <option value="">Select reason...</option>
                        <option value="PC unavailable">PC unavailable</option>
                        <option value="Lab closed for maintenance">Lab closed for maintenance</option>
                        <option value="Conflicting schedule">Conflicting schedule</option>
                        <option value="Insufficient student credits">Insufficient student credits</option>
                        <option value="Invalid purpose">Invalid purpose</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 rounded-b-xl">
                    <button type="button" id="cancelDisapproveBtn" class="px-4 py-2.5 bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Confirm Disapproval
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {        
        // Check URL parameters for preserved PC status
        const urlParams = new URLSearchParams(window.location.search);
        const preservePc = urlParams.get('preserve_pc');
        const preserveLab = urlParams.get('preserve_lab');
        const preserveStatus = urlParams.get('preserve_status');

        // If we have preserved status parameters, apply them immediately
        if (preservePc && preserveLab && preserveStatus) {
            // Find the PC button
            setTimeout(() => {
                const pcButton = document.querySelector(
                    `.toggle-pc-btn[data-pc-id="${preservePc}"][data-lab-room="${preserveLab}"]`
                );
                
                if (pcButton) {
                    // Update the button's appearance to match the preserved status
                    const parentItem = pcButton.closest('.pc-item');
                    
                    // Remove all status classes
                    pcButton.classList.remove('bg-green-500', 'bg-red-500', 'bg-purple-500', 'bg-gray-400');
                    pcButton.classList.remove('hover:bg-green-600', 'hover:bg-red-600', 'hover:bg-purple-600', 'hover:bg-gray-500');
                    
                    // Apply the preserved status
                    if (preserveStatus === 'reserved') {
                        pcButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                        const icon = pcButton.querySelector('i');
                        if (icon) icon.className = 'fas fa-calendar-check mb-1 text-lg';
                        pcButton.setAttribute('data-new-status', 'available');
                        parentItem.setAttribute('data-status', 'reserved');
                    }
                }
                
                // Clean up URL after applying preserved status
                const cleanUrl = window.location.href.split('?')[0] + 
                    (urlParams.has('lab') ? '?lab=' + urlParams.get('lab') : '');
                window.history.replaceState({}, document.title, cleanUrl);
            }, 100); // Small delay to ensure DOM is fully loaded
        }

        // Attach event listeners to PC toggle buttons
        attachPCToggleListeners();
        
        // Lab room selector change - AJAX version
        document.getElementById('labRoomSelector').addEventListener('change', function() {
            const selectedLab = this.value;
            
            // Show loading state
            const pcGrid = document.getElementById('pcGrid');
            pcGrid.innerHTML = `<div class="col-span-5 py-10 text-center">
                <div class="inline-flex items-center justify-center">
                    <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full spinner"></div>
                </div>
                <p class="text-gray-500 mt-3">Loading computers...</p>
            </div>`;
            
            // Fetch computers for the selected lab
            fetch(`admin_reservation.php?lab=${selectedLab}&ajax=1`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error("Invalid JSON response:", text);
                        throw new Error("Server returned invalid JSON");
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Update the PC grid with new computers
                    pcGrid.innerHTML = '';
                    
                    // Update URL without refreshing page
                    window.history.pushState({}, '', `?lab=${selectedLab}`);
                    
                    // Update room title
                    document.querySelector('#pcGrid').previousElementSibling.textContent = `Room ${selectedLab} Computers`;
                    
                    // Create and add PC buttons
                    data.computers.forEach(pc => {
                        const pcItem = createPCButton(pc.id, pc.status, selectedLab);
                        pcGrid.appendChild(pcItem);
                    });
                    
                    // Reattach event listeners to new buttons
                    attachPCToggleListeners();
                    
                    // Update stats
                    document.querySelectorAll('.pc-filter').forEach(filter => {
                        const filterType = filter.getAttribute('data-filter');
                        if (filterType === 'all') {
                            filter.textContent = `All (${data.computers.length})`;
                        } else if (filterType === 'available') {
                            filter.textContent = `Available (${data.available_count})`;
                        } else if (filterType === 'used') {
                            filter.textContent = `Used (${data.used_count})`;
                        }
                    });
                } else {
                    showAlert('Error', 'Failed to load computers for the selected lab', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error', 'An unexpected error occurred when loading computers', 'error');
            });
        });
            
        // PC search functionality
        const pcSearchInput = document.getElementById('pcSearchInput');
        pcSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.pc-item').forEach(item => {
                const pcNum = item.getAttribute('data-pc-num');
                if (pcNum.toString().includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // PC filter functionality
        document.querySelectorAll('.pc-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Update active filter button
                document.querySelectorAll('.pc-filter').forEach(btn => {
                    btn.classList.remove('bg-blue-500', 'text-white', 'border-transparent');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
                });
                
                this.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
                this.classList.add('bg-blue-500', 'text-white', 'border-transparent');
                
                // Apply filter
                const filter = this.getAttribute('data-filter');
                document.querySelectorAll('.pc-item').forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-status') === filter) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Request search functionality
        const requestSearchInput = document.getElementById('requestSearchInput');
        requestSearchInput.addEventListener('input', function() {
            filterRequests();
        });
        
        // Request lab filter
        const requestLabFilter = document.getElementById('requestLabFilter');
        requestLabFilter.addEventListener('change', function() {
            filterRequests();
        });
        
        // Request sort order
        const requestSortOrder = document.getElementById('requestSortOrder');
        requestSortOrder.addEventListener('change', function() {
            sortRequests();
        });
        
        // Log search functionality
        const logSearchInput = document.getElementById('logSearchInput');
        logSearchInput.addEventListener('input', function() {
            filterLogs();
        });
        
        // Log filter functionality
        document.querySelectorAll('.log-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Update active filter button
                document.querySelectorAll('.log-filter').forEach(btn => {
                    btn.classList.remove('bg-blue-500', 'text-white', 'border-transparent');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
                });
                
                this.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
                this.classList.add('bg-blue-500', 'text-white', 'border-transparent');
                
                // Apply filter
                filterLogs();
            });
        });
        
        function filterRequests() {
            const searchTerm = requestSearchInput.value.toLowerCase();
            const labFilter = requestLabFilter.value;
            const requestItems = document.querySelectorAll('.request-item');
            let anyVisible = false;
            
            requestItems.forEach(item => {
                const studentName = item.getAttribute('data-student-name');
                const studentId = item.getAttribute('data-student-id');
                const lab = item.getAttribute('data-lab');
                const purpose = item.getAttribute('data-purpose');
                
                const matchesSearch = studentName.includes(searchTerm) || 
                                     studentId.includes(searchTerm) || 
                                     purpose.includes(searchTerm);
                const matchesLab = !labFilter || lab === labFilter;
                
                if (matchesSearch && matchesLab) {
                    item.style.display = '';
                    anyVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide the "No requests" message
            const noRequestsMessage = document.getElementById('noRequestsMessage');
            if (noRequestsMessage) {
                noRequestsMessage.style.display = anyVisible ? 'none' : 'block';
            }
        }
        
        function sortRequests() {
            const requestsList = document.getElementById('requestsList');
            const sortOrder = requestSortOrder.value;
            const requestItems = Array.from(document.querySelectorAll('.request-item'));
            
            requestItems.sort((a, b) => {
                const dateA = parseInt(a.getAttribute('data-date'));
                const dateB = parseInt(b.getAttribute('data-date'));
                
                if (sortOrder === 'date_asc') {
                    return dateA - dateB;
                } else {
                    return dateB - dateA;
                }
            });
            
            // Remove all items and re-append in the sorted order
            requestItems.forEach(item => item.remove());
            requestItems.forEach(item => requestsList.appendChild(item));
        }
        
        function filterLogs() {
            const searchTerm = logSearchInput.value.toLowerCase();
            const activeFilter = document.querySelector('.log-filter.bg-blue-500').getAttribute('data-filter');
            const logItems = document.querySelectorAll('.log-item');
            let anyVisible = false;
            
            logItems.forEach(item => {
                const studentName = item.getAttribute('data-student-name');
                const studentId = item.getAttribute('data-student-id');
                const status = item.getAttribute('data-status');
                
                const matchesSearch = studentName.includes(searchTerm) || 
                                     studentId.includes(searchTerm);
                const matchesFilter = activeFilter === 'all' || status === activeFilter;
                
                if (matchesSearch && matchesFilter) {
                    item.style.display = '';
                    anyVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide the "No logs" message
            const noLogsMessage = document.getElementById('noLogsMessage');
            if (noLogsMessage) {
                noLogsMessage.style.display = anyVisible ? 'none' : 'block';
            }
        }
        
        // Show alerts with SweetAlert2
        function showAlert(title, message, icon) {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
        
        // Show toast notification
        function showToast(message, icon) {
            Swal.fire({
                text: message,
                icon: icon,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
        
        // Handle request approval with AJAX
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                  
                const requestId = parseInt(this.getAttribute('data-request-id'));
                
                if (!requestId || isNaN(requestId)) {
                    showAlert('Error', 'Invalid request ID detected. Please try again.', 'error');
                    return;
                }
                
                Swal.fire({
                    title: 'Confirm Approval',
                    text: 'Are you sure you want to approve this reservation request?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn-transition',
                        cancelButton: 'btn-transition'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Approving the reservation request',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Send AJAX request
                        const formData = new FormData();
                        formData.append('approve_request', '1');
                        formData.append('request_id', requestId.toString()); 
                        
                        fetch('conn_back/reservation_process.php', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error("Invalid JSON response:", text);
                                    throw new Error("Server returned invalid JSON");
                                }
                            });
                        })
                        .then(data => {
                            if (data.success) {
                                // Get the PC button element in the grid
                                const pcButton = document.querySelector(`.toggle-pc-btn[data-pc-id="${data.pc_number}"][data-lab-room="${data.lab_room}"]`);
                                
                                if (pcButton) {
                                    // Update the button's appearance to match the reserved status
                                    const parentItem = pcButton.closest('.pc-item');
                                    
                                    // Remove all status classes
                                    pcButton.classList.remove('bg-green-500', 'bg-red-500', 'bg-purple-500', 'bg-gray-400');
                                    pcButton.classList.remove('hover:bg-green-600', 'hover:bg-red-600', 'hover:bg-purple-600', 'hover:bg-gray-500');
                                    
                                    // Add the appropriate class based on returned status
                                    pcButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                                    const icon = pcButton.querySelector('i');
                                    icon.className = 'fas fa-calendar-check mb-1 text-lg';
                                    pcButton.setAttribute('data-new-status', 'available');
                                    parentItem.setAttribute('data-status', 'reserved');
                                }
                                
                                Swal.fire({
                                    title: 'Approved!',
                                    text: 'The reservation has been approved successfully.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    // Instead of a full page reload, we'll reload with a PC status parameter
                                    window.location.href = window.location.href + 
                                        (window.location.href.includes('?') ? '&' : '?') + 
                                        'preserve_pc=' + data.pc_number + 
                                        '&preserve_lab=' + data.lab_room + 
                                        '&preserve_status=reserved';
                                });
                            } else {
                                showAlert('Error', data.message || 'An error occurred while approving the reservation.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Error', 'An unexpected error occurred. Please try again.', 'error');
                        });
                    }
                });
            });
        });
                
        // Handle request disapproval (show modal)
        document.querySelectorAll('.disapprove-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = parseInt(this.getAttribute('data-request-id'));

                // Validate request ID
                if (!requestId || isNaN(requestId)) {
                    showAlert('Error', 'Invalid request ID detected. Please try again.', 'error');
                    return;
                }
        
                document.getElementById('disapprove_request_id').value = requestId;
                document.getElementById('disapproveModal').classList.remove('hidden');
            });
        });
        
        // Hide disapproval modal
        document.getElementById('cancelDisapproveBtn').addEventListener('click', function() {
            document.getElementById('disapproveModal').classList.add('hidden');
        });
        
        document.getElementById('closeDisapproveBtn').addEventListener('click', function() {
            document.getElementById('disapproveModal').classList.add('hidden');
        });
        
        // Close modal when clicking outside
        document.getElementById('disapproveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Handle disapproval form submission with AJAX
        document.getElementById('disapproveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const requestId = parseInt(document.getElementById('disapprove_request_id').value);
            const reason = document.getElementById('disapproval_reason').value;

            // Validate the request ID
            if (!requestId || isNaN(requestId)) {
                showAlert('Error', 'Invalid request ID. Please try again.', 'error');
                return;
            }
            
            if (!reason) {
                showAlert('Error', 'Please select a reason for disapproval', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Processing...',
                text: 'Disapproving the reservation request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send AJAX request
            const formData = new FormData();
            formData.append('disapprove_request', '1');
            formData.append('request_id', requestId.toString()); 
            formData.append('reason', reason);
            
            fetch('conn_back/reservation_process.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error("Invalid JSON response:", text);
                        throw new Error("Server returned invalid JSON");
                    }
                });
            })
            .then(data => {
                document.getElementById('disapproveModal').classList.add('hidden');
                
                if (data.success) {
                    Swal.fire({
                        title: 'Disapproved',
                        text: 'The reservation request has been disapproved.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    showAlert('Error', data.message || 'An error occurred while disapproving the reservation.', 'error');
                }
            })
            .catch(error => {
                document.getElementById('disapproveModal').classList.add('hidden');
                console.error('Error:', error);
                showAlert('Error', 'An unexpected error occurred. Please try again.', 'error');
            });
        });
        
        // Refresh button
        document.getElementById('refreshBtn').addEventListener('click', function() {
            const btn = this;
            const originalHTML = btn.innerHTML;
            
            // Show spinner
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            // Reload after a short delay
            setTimeout(() => {
                location.reload();
            }, 300);
        });
    });

    // Helper function to create PC buttons
    // In your <script> section, update the createPCButton function:
    function createPCButton(pcNumber, status, labRoom) {
        let statusColor, hoverColor, icon, nextStatus, tooltipText;

        if (status === 'available') {
            statusColor = 'bg-green-500';
            hoverColor = 'hover:bg-green-600';
            icon = 'fa-check-circle';
            nextStatus = 'used';
            tooltipText = 'Available - Click to mark as In Use';
        } else if (status === 'used') {
            statusColor = 'bg-red-500';
            hoverColor = 'hover:bg-red-600';
            icon = 'fa-times-circle';
            nextStatus = 'available';
            tooltipText = 'Currently In Use - Click to mark as Available';
        } else if (status === 'reserved') {
            statusColor = 'bg-purple-500';
            hoverColor = 'hover:bg-purple-600';
            icon = 'fa-calendar-check';
            nextStatus = 'available';
            tooltipText = 'Reserved for Future Use - This PC is booked for an upcoming reservation';
        } else {
            statusColor = 'bg-gray-400';
            hoverColor = 'hover:bg-gray-500';
            icon = 'fa-question-circle';
            nextStatus = 'available';
            tooltipText = 'Status Unknown - Click to mark as Available';
        }

        const pcItem = document.createElement('div');
        pcItem.className = 'pc-item';
        pcItem.setAttribute('data-status', status);
        pcItem.setAttribute('data-pc-num', pcNumber);
        
        pcItem.innerHTML = `
            <button type="button" class="pc-button toggle-pc-btn ${statusColor} ${hoverColor} text-white shadow-sm btn-transition relative group"
                data-pc-id="${pcNumber}"
                data-new-status="${nextStatus}"
                data-lab-room="${labRoom}"
                title="${tooltipText}">
                <i class="fas ${icon} mb-1 text-lg"></i>
                <span class="text-xs font-medium">PC${pcNumber}</span>
                
                <!-- Enhanced Tooltip -->
                <div class="absolute opacity-0 group-hover:opacity-100 transition-opacity duration-300 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 z-50 pointer-events-none">
                    <div class="bg-gray-900 text-white text-xs rounded py-1.5 px-2 shadow-lg">
                        <p class="font-medium">${tooltipText}</p>
                        ${status === 'reserved' ? '<p class="text-purple-300 mt-1 text-[10px]">Auto-resets to Available after 30 minutes</p>' : ''}
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                </div>
            </button>
        `;
        
        return pcItem;
    }

    // Function to attach event listeners to PC toggle buttons
    function attachPCToggleListeners() {
        document.querySelectorAll('.toggle-pc-btn').forEach(button => {
            button.addEventListener('click', function() {
                const pcId = this.getAttribute('data-pc-id');
                const newStatus = this.getAttribute('data-new-status');
                const labRoom = this.getAttribute('data-lab-room');
                const btn = this;
                
                // Change icon to spinner
                const icon = btn.querySelector('i');
                const originalIconClass = icon.className;
                icon.className = 'fas fa-spinner fa-spin mb-1 text-lg';
                btn.disabled = true;
                
                // Send AJAX request
                const formData = new FormData();
                formData.append('toggle_pc', '1');
                formData.append('pc_id', pcId);
                formData.append('new_status', newStatus);
                formData.append('lab_room', labRoom);
                
                fetch('admin_reservation.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error("Invalid JSON response:", text);
                            throw new Error("Server returned invalid JSON");
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        // Update button appearance
                        btn.classList.remove('bg-green-500', 'bg-red-500', 'bg-amber-500', 'bg-gray-400');
                        btn.classList.remove('hover:bg-green-600', 'hover:bg-red-600', 'hover:bg-amber-600', 'hover:bg-gray-500');
                        
                        // Update icon and color
                        if (data.status === 'available') {
                            btn.classList.add('bg-green-500', 'hover:bg-green-600');
                            icon.className = 'fas fa-check-circle mb-1 text-lg';
                            btn.setAttribute('data-new-status', 'used');
                        } else if (data.status === 'used') {
                            btn.classList.add('bg-red-500', 'hover:bg-red-600');
                            icon.className = 'fas fa-times-circle mb-1 text-lg';
                            btn.setAttribute('data-new-status', 'available');
                        } else if (data.status === 'reserved') {
                            btn.classList.add('bg-purple-500', 'hover:bg-purple-600');
                            icon.className = 'fas fa-calendar-check mb-1 text-lg';
                            btn.setAttribute('data-new-status', 'available');
                            btn.setAttribute('title', 'Reserved for Future Use - This PC is booked for an upcoming reservation');
                            
                            // Add this code to update the tooltip content inside the button
                            const tooltip = btn.querySelector('.group-hover\\:opacity-100 .bg-gray-900 p:first-child');
                            if (tooltip) {
                                tooltip.textContent = 'Reserved for Future Use - This PC is booked for an upcoming reservation';
                            }
                            
                            // Add the small text about auto-reset if not already present
                            const tooltipContainer = btn.querySelector('.group-hover\\:opacity-100 .bg-gray-900');
                            if (tooltipContainer) {
                                if (!tooltipContainer.querySelector('.text-purple-300')) {
                                    const autoResetText = document.createElement('p');
                                    autoResetText.className = 'text-purple-300 mt-1 text-[10px]';
                                    autoResetText.textContent = 'Auto-resets to Available after 30 minutes';
                                    tooltipContainer.appendChild(autoResetText);
                                }
                            }
                        } else {
                            btn.classList.add('bg-gray-400', 'hover:bg-gray-500');
                            icon.className = 'fas fa-question-circle mb-1 text-lg';
                            btn.setAttribute('data-new-status', 'available');
                        }
                        
                        // Update parent element status
                        btn.closest('.pc-item').setAttribute('data-status', data.status);
                        
                        // Show success toast
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: `PC ${pcId} updated to ${data.status}`,
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            timerProgressBar: true
                        });
                    } else {
                        // Restore original icon on error
                        icon.className = originalIconClass;
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to update PC status',
                            icon: 'error'
                        });
                    }
                    console.log('Response data:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Restore original icon on error
                    icon.className = originalIconClass;
                    Swal.fire({
                        title: 'Error',
                        text: 'An unexpected error occurred',
                        icon: 'error'
                    });
                })
                .finally(() => {
                    btn.disabled = false;
                });
            });
        });
    }
</script>
</body>
</html>

<iframe src="conn_back/reset_pc.php" style="display:none;" id="resetFrame"></iframe>

<script>
    // Refresh the reset frame every 5 minutes
    setInterval(function() {
        document.getElementById('resetFrame').src = 'conn_back/reset_pc.php?t=' + new Date().getTime();
    }, 300000);
</script>