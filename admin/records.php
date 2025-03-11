<?php
include '../conn/dbcon.php';

// Initialize variables for filtering
$filterLab = isset($_GET['laboratory']) ? $_GET['laboratory'] : '';
$filterDate = isset($_GET['date']) ? $_GET['date'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare base query
$query = "SELECT s.sit_in_id, s.user_id, s.laboratory, s.purpose, s.check_in_time, s.check_out_time, s.status,
                 u.idno, u.firstname, u.midname, u.lastname, u.course, u.level
          FROM curr_sit_in s
          JOIN users u ON s.user_id = u.id
          WHERE 1=1";

// Add filters to query
$params = [];
$types = "";

if (!empty($filterLab)) {
    $query .= " AND s.laboratory = ?";
    $params[] = $filterLab;
    $types .= "s";
}

if (!empty($filterDate)) {
    $query .= " AND DATE(s.check_in_time) = ?";
    $params[] = $filterDate;
    $types .= "s";
}

if (!empty($filterStatus)) {
    $query .= " AND s.status = ?";
    $params[] = $filterStatus;
    $types .= "s";
}

if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $query .= " AND (u.idno LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ? OR CONCAT(u.firstname, ' ', u.lastname) LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ssss";
}

// Add ordering
$query .= " ORDER BY s.check_in_time DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get statistics for dashboard
$statsQuery = "SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_sessions,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_sessions,
                COUNT(DISTINCT user_id) as unique_students,
                SUM(CASE WHEN check_out_time IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time) 
                    ELSE 0 END) as total_minutes
               FROM curr_sit_in";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Get list of laboratories for filter
$labQuery = "SELECT DISTINCT laboratory FROM curr_sit_in ORDER BY laboratory";
$labResult = $conn->query($labQuery);
$laboratories = [];
while ($row = $labResult->fetch_assoc()) {
    $laboratories[] = $row['laboratory'];
}

// Get dates for filter (unique dates from check_in_time)
$dateQuery = "SELECT DISTINCT DATE(check_in_time) as date FROM curr_sit_in ORDER BY date DESC";
$dateResult = $conn->query($dateQuery);
$dates = [];
while ($row = $dateResult->fetch_assoc()) {
    $dates[] = $row['date'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Records | CCS Sit-In System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar_admin.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Page Title -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-history mr-2"></i> Session Records
            </h1>
            <div>
                <button onclick="exportToCSV()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-export"></i> Export to CSV
                </button>
            </div>
        </div>
        
        <!-- Stats Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Total Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-600">Total Sessions</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['total_sessions']; ?></span>
                </div>
            </div>
            
            <!-- Active Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-green-600">Active Sessions</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['active_sessions']; ?></span>
                </div>
            </div>
            
            <!-- Completed Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                <h3 class="text-lg font-semibold text-purple-600">Completed</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['completed_sessions']; ?></span>
                </div>
            </div>
            
            <!-- Unique Students -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <h3 class="text-lg font-semibold text-yellow-600">Unique Students</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['unique_students']; ?></span>
                </div>
            </div>
            
            <!-- Total Hours -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-semibold text-red-600">Total Hours</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo floor($stats['total_minutes'] / 60); ?></span>
                    <span class="ml-2 text-sm text-gray-500">hrs</span>
                </div>
            </div>
        </div>
        
        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Laboratory Filter -->
                <div>
                    <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratory</label>
                    <select name="laboratory" id="laboratory" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Laboratories</option>
                        <?php foreach($laboratories as $lab): ?>
                            <option value="<?php echo $lab; ?>" <?php echo ($filterLab == $lab) ? 'selected' : ''; ?>>
                                <?php echo $lab; ?>
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
                    <table class="min-w-full divide-y divide-gray-200" id="recordsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratory</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
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
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($row['purpose']); ?>">
                                            <?php echo htmlspecialchars($row['purpose']); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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
        
        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Laboratory Usage Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Laboratory Usage</h3>
                <canvas id="labChart" height="200"></canvas>
            </div>
            
            <!-- Daily Sessions Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Activity</h3>
                <canvas id="activityChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Export table data to CSV
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

        // Load charts after page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Lab usage chart data - you would populate this from backend
            const labChartCtx = document.getElementById('labChart').getContext('2d');
            const labChart = new Chart(labChartCtx, {
                type: 'pie',
                data: {
                    labels: <?php 
                        $labCountQuery = "SELECT laboratory, COUNT(*) as count 
                                         FROM curr_sit_in 
                                         GROUP BY laboratory 
                                         ORDER BY count DESC";
                        $labCountResult = $conn->query($labCountQuery);
                        $labLabels = [];
                        $labData = [];
                        while ($row = $labCountResult->fetch_assoc()) {
                            $labLabels[] = $row['laboratory'];
                            $labData[] = $row['count'];
                        }
                        echo json_encode($labLabels);
                    ?>,
                    datasets: [{
                        data: <?php echo json_encode($labData); ?>,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
            
            // Daily activity chart
            const activityChartCtx = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(activityChartCtx, {
                type: 'bar',
                data: {
                    labels: <?php 
                        $dateActivityQuery = "SELECT DATE(check_in_time) as date, COUNT(*) as count 
                                           FROM curr_sit_in 
                                           GROUP BY DATE(check_in_time) 
                                           ORDER BY date DESC 
                                           LIMIT 7";
                        $dateActivityResult = $conn->query($dateActivityQuery);
                        $dateLabels = [];
                        $dateData = [];
                        while ($row = $dateActivityResult->fetch_assoc()) {
                            $dateLabels[] = date('M d', strtotime($row['date']));
                            $dateData[] = $row['count'];
                        }
                        $dateLabels = array_reverse($dateLabels);
                        $dateData = array_reverse($dateData);
                        echo json_encode($dateLabels);
                    ?>,
                    datasets: [{
                        label: 'Number of Sessions',
                        data: <?php echo json_encode($dateData); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>