<?php include("./conn_back/records_process.php"); ?>
<?php include("./conn_back/reports_process.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Records | CCS Sit-In System</title>
    <?php include("icon.php"); ?>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a0a0a0;
    }
    

    #recordsTable tbody tr {
        height: 64px;
    }
    
  
    #recordsTable thead {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f9fafb; 
    }
</style>
<style>
    /* Calculate: 8 rows × row height (64px) + header height (53px) = 565px */
    .max-h-\[500px\] {
        max-height: 565px;
    }
</style>
<body class="bg-gray-100">
    <?php include 'navbar_admin.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Page Title -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-chart-bar mr-2"></i> Reports
            </h1>
            <div class="flex items-center gap-2">
                <button onclick="exportToCSV()" class="bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                            
                <button onclick="exportToExcel()" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                
                <button onclick="exportToPDF()" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                
                <button onclick="printTable()" class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <!-- Stats Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6">
            <!-- Key Stats -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Key Metrics</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Last 30 days
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Session Stats -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 p-3 rounded-lg bg-blue-50">
                                <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-500">Sessions & Users</h4>
                                <div class="mt-2 flex flex-col">
                                    <div class="flex items-baseline">
                                        <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['total_sessions']); ?></p>
                                        <span class="ml-2 text-sm text-gray-500">sessions</span>
                                    </div>
                                    <div class="flex items-center mt-1 text-sm">
                                        <span class="font-medium text-gray-700"><?php echo number_format($stats['total_students']); ?></span>
                                        <span class="ml-1 text-gray-500">unique students</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Stats -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 p-3 rounded-lg bg-green-50">
                                <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-500">Usage Time</h4>
                                <div class="mt-2 flex flex-col">
                                    <div class="flex items-baseline">
                                        <p class="text-2xl font-bold text-gray-900"><?php echo $totalHours; ?></p>
                                        <span class="ml-2 text-sm text-gray-500">hrs <?php echo $totalMinutes; ?>m total</span>
                                    </div>
                                    <div class="flex items-center mt-1 text-sm">
                                        <span class="font-medium text-gray-700"><?php echo $avgHours; ?>h <?php echo $avgMinutesRemaining; ?>m</span>
                                        <span class="ml-1 text-gray-500">avg session</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Daily Average Trend -->
                        <div class="col-span-2 mt-2">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-medium text-gray-500">Daily Usage Trend</h4>
                                    <span class="text-xs font-medium text-gray-500"><?php echo round($avgSessionsPerDay, 1); ?> avg sessions/day</span>
                                </div>
                                
                                <?php 
                                // Calculate a simple trend indicator from the last 7 days of data
                                $recentDays = min(7, count($visits));
                                if ($recentDays >= 2) {
                                    $recentVisits = array_slice($visits, -$recentDays);
                                    $firstHalf = array_sum(array_slice($recentVisits, 0, floor($recentDays/2))) / floor($recentDays/2);
                                    $secondHalf = array_sum(array_slice($recentVisits, floor($recentDays/2))) / ceil($recentDays/2);
                                    $trend = $secondHalf - $firstHalf;
                                    $trendPercent = ($firstHalf > 0) ? ($trend / $firstHalf) * 100 : 0;
                                } else {
                                    $trend = 0;
                                    $trendPercent = 0;
                                }
                                
                                // Determine trend direction and styling
                                $trendDirection = $trend > 0 ? 'up' : ($trend < 0 ? 'down' : 'stable');
                                $trendColor = $trend > 0 ? 'text-green-600' : ($trend < 0 ? 'text-red-600' : 'text-gray-500');
                                $trendBg = $trend > 0 ? 'bg-green-100' : ($trend < 0 ? 'bg-red-100' : 'bg-gray-100');
                                $trendIcon = $trend > 0 ? 'fa-arrow-up' : ($trend < 0 ? 'fa-arrow-down' : 'fa-minus');
                                ?>
                                
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-4">
                                        <?php 
                                        // Create a simplified visual representation of recent daily trends
                                        foreach(array_slice($visits, -14) as $index => $visit): 
                                            $maxVisit = max(array_slice($visits, -14));
                                            $width = $maxVisit > 0 ? ($visit / $maxVisit) * 100 : 0;
                                            $color = 'bg-blue-500';
                                            if ($index >= count(array_slice($visits, -14)) - 3) {
                                                $color = $trendDirection == 'up' ? 'bg-green-500' : ($trendDirection == 'down' ? 'bg-red-500' : 'bg-blue-500');
                                            }
                                        ?>
                                            <div class="h-2.5 <?php echo $color; ?> rounded-full" style="width: <?php echo $width; ?>%; display: inline-block; margin-right: 2px;"></div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?php echo $trendBg; ?> <?php echo $trendColor; ?>">
                                            <i class="fas <?php echo $trendIcon; ?> mr-1"></i>
                                            <?php echo abs(round($trendPercent, 1)); ?>%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Daily Visit Chart -->
            <div class="lg:col-span-3 bg-white rounded-xl shadow-sm p-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-base font-medium text-gray-900">Daily Visits</h3>
                        <p class="text-sm text-gray-500">Usage trends over time</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Last 30 days
                    </span>
                </div>
                <div class="w-full h-52">
                    <canvas id="dailyVisitsChart"></canvas>
                </div>
            </div>
        </div>

        

        <!-- Second Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <!-- Lab Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-base font-medium text-gray-900">Lab Distribution</h3>
                        <p class="text-sm text-gray-500">Usage by laboratory</p>
                    </div>
                </div>
                <div class="w-full h-56">
                    <canvas id="labDistributionChart"></canvas>
                </div>
            </div>
            
            <!-- Peak Hours -->
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-base font-medium text-gray-900">Peak Hours</h3>
                        <p class="text-sm text-gray-500">Busiest times of day</p>
                    </div>
                </div>
                <div class="w-full h-56">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>
            
            <!-- Top Purpose Card -->
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <h3 class="text-base font-medium text-gray-900 mb-3">Top Visit Purposes</h3>
                <?php if (!empty($purposeLabels)): ?>
                    <div class="space-y-3">
                        <?php foreach($purposeLabels as $index => $purpose): ?>
                            <?php 
                                // Calculate percentage
                                $percentage = ($purposeCounts[$index] / $stats['total_sessions']) * 100;
                                // Generate different colors for the bars
                                $colors = ['bg-blue-500', 'bg-indigo-500', 'bg-purple-500', 'bg-pink-500', 'bg-red-500'];
                                $color = $colors[$index % count($colors)];
                            ?>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700 truncate max-w-[70%]" title="<?php echo htmlspecialchars($purpose); ?>">
                                        <?php echo htmlspecialchars($purpose); ?>
                                    </span>
                                    <span class="text-xs text-gray-500"><?php echo $purposeCounts[$index]; ?></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="<?php echo $color; ?> h-1.5 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-gray-500">No data available</p>
                <?php endif; ?>
            </div>
            
            <!-- Insights Card -->
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <h3 class="text-base font-medium text-gray-900 mb-3">Quick Insights</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Busiest Day</p>
                            <p class="text-sm text-gray-600"><?php echo $busiestDay['day_name'] ?? 'No data'; ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Avg. Daily Sessions</p>
                            <p class="text-sm text-gray-600"><?php echo $avgSessionsPerDay; ?> sessions</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Report Period</p>
                            <p class="text-sm text-gray-600"><?php echo date('M d', strtotime($startDate)); ?> - <?php echo date('M d', strtotime($endDate)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Laboratory Filter -->
                <div>
                    <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                    <select name="laboratory" id="laboratory" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Rooms</option>
                        <?php foreach($laboratories as $lab): ?>
                            <option value="<?php echo $lab; ?>" <?php echo ($filterLab == $lab) ? 'selected' : ''; ?>>
                                Room <?php echo $lab; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select name="date" id="date" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Dates</option>
                        <?php foreach($dates as $date): ?>
                            <option value="<?php echo $date; ?>" <?php echo ($filterDate == $date) ? 'selected' : ''; ?>>
                                <?php echo date('F j, Y', strtotime($date)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($filterStatus == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="completed" <?php echo ($filterStatus == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" placeholder="Search by ID or name" 
                           class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                </div>
                
                <!-- Apply Filters Button -->
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors w-full">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Records Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <?php if ($result->num_rows > 0): ?>
                    <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-200" id="recordsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratory</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time-In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time-Out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <?php 
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
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($row['idno']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . ($row['midname'] ? $row['midname'][0] . '.' : '')); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($row['course'] . ' - ' . $row['level']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?php echo htmlspecialchars($row['laboratory']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($row['purpose']); ?>">
                                                <?php echo htmlspecialchars($row['purpose']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y g:i A', strtotime($row['check_in_time'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row['check_out_time'] ? date('M d, Y g:i A', strtotime($row['check_out_time'])) : 'Still Active'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $duration; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $row['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-10">
                        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-gray-100 rounded-full">
                            <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Records Found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try changing your search filters</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Existing CSV export function
        function exportToCSV() {
            const table = document.getElementById('recordsTable');
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    // Get the text content and replace any commas to avoid CSV issues
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/,/g, ';');
                    row.push('"' + data + '"');
                }
                
                csv.push(row.join(','));
            }
            
            // Download CSV file
            const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
            const downloadLink = document.createElement('a');
            
            downloadLink.download = 'sit_in_records_' + new Date().toISOString().slice(0,10) + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        
        // New Excel export function
        function exportToExcel() {
            const table = document.getElementById('recordsTable');
            const wb = XLSX.utils.table_to_book(table, {sheet: "Session Records"});
            const filename = 'sit_in_records_' + new Date().toISOString().slice(0,10) + '.xlsx';
            
            XLSX.writeFile(wb, filename);
        }
        
        // New PDF export function 
        function exportToPDF() {
            // Initialize jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'pt', 'a4');
            
            // Add title
            doc.setFontSize(18);
            doc.text('Session Records Report', 40, 40);
            
            // Add date
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text('Generated on: ' + new Date().toLocaleString(), 40, 60);
            
            // From the table, create the PDF
            doc.autoTable({
                html: '#recordsTable',
                startY: 70,
                margin: { top: 70, right: 40, bottom: 40, left: 40 },
                styles: { fontSize: 8, cellPadding: 2 },
                columnStyles: {
                    0: { cellWidth: 'auto' }, // ID
                    1: { cellWidth: 'auto' }, // Student
                    2: { cellWidth: 'auto' }, // Course
                    3: { cellWidth: 40 }, // Laboratory
                    4: { cellWidth: 80 }, // Purpose
                    5: { cellWidth: 65 }, // Time-in
                    6: { cellWidth: 65 }, // Time-out
                    7: { cellWidth: 50 }, // Duration
                    8: { cellWidth: 50 }  // Status
                },
                didDrawCell: function(data) {
                    // Ensure text doesn't overflow
                    if (data.column.index === 4 && data.cell.section === 'body') {
                        const td = data.cell.raw;
                        if (td.offsetWidth > 80) {
                            doc.setFontSize(7);
                        }
                    }
                }
            });
            
            // Save PDF
            doc.save('sit_in_records_' + new Date().toISOString().slice(0,10) + '.pdf');
        }
        
        // Print function
        function printTable() {
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'height=600,width=800');
            
            // Get current page title and institution name
            const title = document.title || 'Session Records';
            const institutionName = 'CCS Sit-In System';
            
            // Build HTML content with title, date and table content
            printWindow.document.write(`
                <html>
                <head>
                    <title>${title} - Print View</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { text-align: center; font-size: 20px; margin-bottom: 5px; }
                        .institution { text-align: center; font-size: 16px; margin-bottom: 20px; }
                        .date { text-align: center; font-size: 14px; margin-bottom: 20px; color: #666; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #f2f2f2; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                        .print-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
                        .filters { margin-bottom: 20px; font-size: 12px; }
                        .filters strong { font-weight: bold; }
                        @media print {
                            button { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <div>
                            <h1>${title}</h1>
                            <div class="institution">${institutionName}</div>
                            <div class="date">Generated on: ${new Date().toLocaleString()}</div>
                        </div>
                    </div>
                    
                    <div class="filters">
                        <strong>Filters:</strong> 
                        Room: ${document.getElementById('laboratory').value || 'All'} | 
                        Date: ${document.getElementById('date').value || 'All'} |
                        Status: ${document.getElementById('status').value || 'All'} |
                        Search: ${document.getElementById('search').value || 'None'}
                    </div>
                    
                    <table>${document.getElementById('recordsTable').outerHTML}</table>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="window.print();window.close();" style="padding: 10px 20px;">
                            Print Document
                        </button>
                    </div>
                </body>
                </html>
            `);
            
            // Close the document
            printWindow.document.close();
            
            // Focus on the new window
            printWindow.focus();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Set up Chart.js defaults
        Chart.defaults.font.family = '"Inter", sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#6B7280';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(17, 24, 39, 0.9)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.titleFont.size = 13;
        Chart.defaults.plugins.tooltip.titleFont.weight = 'bold';
        Chart.defaults.plugins.tooltip.bodyFont.size = 12;
        Chart.defaults.plugins.legend.position = 'bottom';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 15;
        
        // Daily Visits Chart
        const dailyVisitsCtx = document.getElementById('dailyVisitsChart').getContext('2d');
        const dailyVisitsChart = new Chart(dailyVisitsCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Sessions',
                    data: <?php echo json_encode($visits); ?>,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#3B82F6',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#3B82F6',
                    pointHoverBorderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Lab Distribution Chart
        const labDistributionCtx = document.getElementById('labDistributionChart').getContext('2d');
        const labDistributionChart = new Chart(labDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($labLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($labCounts); ?>,
                    backgroundColor: [
                        '#3B82F6', // blue
                        '#6366F1', // indigo
                        '#8B5CF6', // violet
                        '#EC4899', // pink
                        '#EF4444', // red
                        '#F59E0B', // amber
                        '#10B981'  // emerald
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateScale: true
                }
            }
        });
        
        // Peak Hours Chart
        const peakHoursCtx = document.getElementById('peakHoursChart').getContext('2d');
        const peakHoursChart = new Chart(peakHoursCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($hourLabels); ?>,
                datasets: [{
                    label: 'Check-ins',
                    data: <?php echo json_encode($hourCounts); ?>,
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>