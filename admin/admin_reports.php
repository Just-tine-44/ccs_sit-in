<?php include("navbar_admin.php"); ?>
<?php include("./conn_back/reports_process.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics & Reports | CCS Lab System</title>
    <?php include("icon.php"); ?>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header with Date Filter -->
        <div class="mb-6 bg-white rounded-xl shadow-sm p-4 md:p-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">Lab Usage Analytics</h1>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium">Period:</span> 
                        <?php echo date('M d, Y', strtotime($startDate)); ?> - 
                        <?php echo date('M d, Y', strtotime($endDate)); ?>
                    </p>
                </div>
                
                <form method="GET" action="" class="flex flex-wrap items-center gap-2">
                    <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>" 
                        class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-400">to</span>
                    <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>" 
                        class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700 transition">
                            <i class="fas fa-sync-alt mr-1"></i> Update
                        </button>
                        <a href="export_reports.php?start_date=<?php echo $startDate; ?>&end_date=<?php echo $endDate; ?>" 
                           class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-emerald-700 transition">
                            <i class="fas fa-download mr-1"></i> Export
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Key Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <!-- Sessions -->
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-blue-500">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-medium text-gray-500">Sessions</p>
                    <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-desktop text-blue-500"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo number_format($stats['total_sessions']); ?></h2>
            </div>
            
            <!-- Students -->
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-indigo-500">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-medium text-gray-500">Students</p>
                    <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center">
                        <i class="fas fa-users text-indigo-500"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo number_format($stats['total_students']); ?></h2>
            </div>
            
            <!-- Hours -->
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-emerald-500">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-medium text-gray-500">Total Hours</p>
                    <div class="h-8 w-8 rounded-full bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-clock text-emerald-500"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo $totalHours; ?>:<?php echo str_pad($totalMinutes, 2, '0', STR_PAD_LEFT); ?></h2>
            </div>
            
            <!-- Avg Time -->
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-amber-500">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-medium text-gray-500">Avg Session</p>
                    <div class="h-8 w-8 rounded-full bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-stopwatch text-amber-500"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo $avgHours; ?>:<?php echo str_pad($avgMinutesRemaining, 2, '0', STR_PAD_LEFT); ?></h2>
            </div>
        </div>
        
        <!-- Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Daily Trends -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-medium text-gray-800">Daily Visit Trends</h2>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                        <i class="fas fa-users mr-1"></i> <?php echo array_sum($visits); ?> total
                    </span>
                </div>
                <div class="p-4" style="height: 250px">
                    <canvas id="dailyTrendChart"></canvas>
                </div>
            </div>
            
            <!-- Lab Usage -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-medium text-gray-800">Laboratory Distribution</h2>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                        <i class="fas fa-building mr-1"></i> <?php echo count($labLabels); ?> labs
                    </span>
                </div>
                <div class="p-4" style="height: 250px">
                    <canvas id="labUsageChart"></canvas>
                </div>
            </div>
            
            <!-- Purpose Distribution -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-medium text-gray-800">Visit Purposes</h2>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                        <i class="fas fa-clipboard-list mr-1"></i> Top 5
                    </span>
                </div>
                <div class="p-4" style="height: 250px">
                    <canvas id="purposeChart"></canvas>
                </div>
            </div>
            
            <!-- Usage by Lab Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h2 class="font-medium text-gray-800">Lab Usage Summary</h2>
                </div>
                <div class="p-4 overflow-auto" style="max-height: 250px">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-xs text-gray-500 uppercase text-left tracking-wider">
                                <th class="py-2 px-3 bg-gray-50 rounded-l-lg">Laboratory</th>
                                <th class="py-2 px-3 bg-gray-50 text-center">Sessions</th>
                                <th class="py-2 px-3 bg-gray-50 rounded-r-lg text-center">% of Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            $labResult->data_seek(0);
                            $totalSessions = array_sum($labCounts);
                            while ($row = $labResult->fetch_assoc()): 
                                $percentage = ($row['count'] / $totalSessions) * 100;
                            ?>
                                <tr class="text-sm">
                                    <td class="py-2 px-3 font-medium text-gray-800"><?php echo $row['laboratory']; ?></td>
                                    <td class="py-2 px-3 text-center"><?php echo $row['count']; ?></td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 mr-2">
                                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                            </div>
                                            <?php echo round($percentage); ?>%
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart colors
        const colors = {
            blue: {
                fill: 'rgba(59, 130, 246, 0.1)',
                stroke: 'rgba(59, 130, 246, 0.8)',
            },
            indigo: {
                fill: 'rgba(99, 102, 241, 0.1)',
                stroke: 'rgba(99, 102, 241, 0.8)',
            },
            emerald: {
                fill: 'rgba(16, 185, 129, 0.1)',
                stroke: 'rgba(16, 185, 129, 0.8)',
            },
            palette: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(99, 102, 241, 0.7)',
                'rgba(139, 92, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(245, 158, 11, 0.7)'
            ]
        };
        
        // Daily Trend Chart
        new Chart(document.getElementById('dailyTrendChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Visits',
                    data: <?php echo json_encode($visits); ?>,
                    borderColor: colors.blue.stroke,
                    backgroundColor: colors.blue.fill,
                    borderWidth: 2,
                    pointRadius: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
        
        // Lab Usage Chart
        new Chart(document.getElementById('labUsageChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labLabels); ?>,
                datasets: [{
                    label: 'Sessions',
                    data: <?php echo json_encode($labCounts); ?>,
                    backgroundColor: colors.palette,
                    borderRadius: 4,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
        
        // Purpose Chart
        new Chart(document.getElementById('purposeChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($purposeLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($purposeCounts); ?>,
                    backgroundColor: colors.palette,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 12, padding: 15 }
                    }
                },
                cutout: '65%'
            }
        });
    });
    </script>
</body>
</html>