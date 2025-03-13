<?php include("navbar_admin.php"); ?>
<?php include("./conn_back/feedback_process.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-In Feedback Reports</title>
    <?php include("icon.php"); ?>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <style>
        .rating-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e5e7eb;
            overflow: hidden;
        }
        .rating-bar-fill {
            height: 100%;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 md:p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Sit-In Feedback Reports</h1>
                <p class="text-gray-500">View and analyze student feedback for laboratory sessions</p>
            </div>
            <div>
                <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-file-export"></i> Export to CSV
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Rating Overview Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Rating Overview</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-gray-800 mb-2"><?php echo number_format($stats['avg_rating'], 1); ?></div>
                            <div class="flex items-center justify-center space-x-1 mb-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= round($stats['avg_rating']) ? 'text-yellow-500' : 'text-gray-300'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-500 text-sm"><?php echo $stats['total_ratings']; ?> ratings</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <!-- 5 stars -->
                        <div class="flex items-center">
                            <div class="flex items-center w-24">
                                <span class="text-sm font-medium text-gray-700 mr-2">5</span>
                                <i class="fas fa-star text-yellow-500"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="rating-bar">
                                    <div class="rating-bar-fill bg-yellow-500" style="width: <?php echo $starPercentages['five']; ?>%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right text-sm text-gray-500"><?php echo $stats['five_star']; ?></div>
                        </div>
                        
                        <!-- 4 stars -->
                        <div class="flex items-center">
                            <div class="flex items-center w-24">
                                <span class="text-sm font-medium text-gray-700 mr-2">4</span>
                                <i class="fas fa-star text-yellow-500"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="rating-bar">
                                    <div class="rating-bar-fill bg-yellow-400" style="width: <?php echo $starPercentages['four']; ?>%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right text-sm text-gray-500"><?php echo $stats['four_star']; ?></div>
                        </div>
                        
                        <!-- 3 stars -->
                        <div class="flex items-center">
                            <div class="flex items-center w-24">
                                <span class="text-sm font-medium text-gray-700 mr-2">3</span>
                                <i class="fas fa-star text-yellow-500"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="rating-bar">
                                    <div class="rating-bar-fill bg-yellow-300" style="width: <?php echo $starPercentages['three']; ?>%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right text-sm text-gray-500"><?php echo $stats['three_star']; ?></div>
                        </div>
                        
                        <!-- 2 stars -->
                        <div class="flex items-center">
                            <div class="flex items-center w-24">
                                <span class="text-sm font-medium text-gray-700 mr-2">2</span>
                                <i class="fas fa-star text-yellow-500"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="rating-bar">
                                    <div class="rating-bar-fill bg-yellow-200" style="width: <?php echo $starPercentages['two']; ?>%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right text-sm text-gray-500"><?php echo $stats['two_star']; ?></div>
                        </div>
                        
                        <!-- 1 star -->
                        <div class="flex items-center">
                            <div class="flex items-center w-24">
                                <span class="text-sm font-medium text-gray-700 mr-2">1</span>
                                <i class="fas fa-star text-yellow-500"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="rating-bar">
                                    <div class="rating-bar-fill bg-yellow-100" style="width: <?php echo $starPercentages['one']; ?>%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right text-sm text-gray-500"><?php echo $stats['one_star']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rating By Laboratory Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Ratings by Laboratory</h2>
                </div>
                <div class="p-6 h-64">
                    <canvas id="labRatingChart"></canvas>
                </div>
            </div>
            
            <!-- Rating Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Rating Trends</h2>
                </div>
                <div class="p-6 h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Laboratory Filter -->
                <div>
                    <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratory</label>
                    <select name="laboratory" id="laboratory" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Laboratories</option>
                        <?php foreach($laboratories as $lab): ?>
                            <option value="<?php echo $lab; ?>" <?php echo ($labFilter == $lab) ? 'selected' : ''; ?>>
                                <?php echo $lab; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Rating Filter -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select name="rating" id="rating" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="0">All Ratings</option>
                        <option value="5" <?php echo ($ratingFilter == 5) ? 'selected' : ''; ?>>5 Stars</option>
                        <option value="4" <?php echo ($ratingFilter == 4) ? 'selected' : ''; ?>>4 Stars</option>
                        <option value="3" <?php echo ($ratingFilter == 3) ? 'selected' : ''; ?>>3 Stars</option>
                        <option value="2" <?php echo ($ratingFilter == 2) ? 'selected' : ''; ?>>2 Stars</option>
                        <option value="1" <?php echo ($ratingFilter == 1) ? 'selected' : ''; ?>>1 Star</option>
                    </select>
                </div>
                
                <!-- Date Range Filter -->
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="text" id="date_range" name="date_range" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $dateFilter; ?>" placeholder="Select date range">
                </div>
                
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchFilter); ?>" placeholder="Student name or feedback..." class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Apply Filters Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Feedback Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Feedback Results</h2>
                <p class="text-sm text-gray-500"><?php echo $result->num_rows; ?> feedback entries found</p>
            </div>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="feedbackTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratory</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted On</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <?php $profileImg = !empty($row['profileImg']) ? '../' . $row['profileImg'] : '../images/person.jpg'; ?>
                                                <img class="h-10 w-10 rounded-full object-cover" src="<?php echo $profileImg; ?>" alt="Student profile">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($row['idno']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?php echo htmlspecialchars($row['laboratory']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($row['check_in_time'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $row['rating'] ? 'text-yellow-500' : 'text-gray-300'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="text-sm text-gray-900 overflow-hidden">
                                            <?php if (!empty($row['feedback'])): ?>
                                                <p class="truncate" title="<?php echo htmlspecialchars($row['feedback']); ?>">
                                                    <?php echo htmlspecialchars($row['feedback']); ?>
                                                </p>
                                                <button class="text-blue-600 hover:text-blue-800 text-xs mt-1" 
                                                        onclick="showFullFeedback('<?php echo addslashes(htmlspecialchars($row['feedback'])); ?>', '<?php echo addslashes(htmlspecialchars($row['firstname'] . ' ' . $row['lastname'])); ?>')">
                                                    Show more
                                                </button>
                                            <?php else: ?>
                                                <span class="text-gray-500">No written feedback</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y g:i A', strtotime($row['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-10">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-comments text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No feedback found</h3>
                    <p class="text-gray-500">Try changing your filters to see more results</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Full Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800" id="modal-student-name"></h3>
                <button onclick="closeFeedbackModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <h4 class="text-sm font-medium text-gray-500 mb-2">Feedback</h4>
                <p id="modal-feedback-text" class="text-gray-800"></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-b-lg flex justify-end">
                <button onclick="closeFeedbackModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize date range picker
        $(function() {
            $('#date_range').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'MM/DD/YYYY'
                }
            });
            
            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });
            
            $('#date_range').on('cancel.daterangepicker', function() {
                $(this).val('');
            });
        });
        
        // Full feedback modal
        function showFullFeedback(feedback, studentName) {
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('modal-feedback-text').textContent = feedback;
            document.getElementById('feedbackModal').classList.remove('hidden');
        }
        
        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('feedbackModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFeedbackModal();
            }
        });
        
        // Export to CSV functionality
        function exportToCSV() {
            const table = document.getElementById('feedbackTable');
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    // Clean text content, handle stars, and replace commas
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/,/g, ';');
                    
                    // Special case for the rating column with stars
                    if (i > 0 && j === 3) {
                        const stars = cols[j].querySelectorAll('.fa-star.text-yellow-500').length;
                        text = stars + ' Stars';
                    }
                    
                    row.push('"' + text + '"');
                }
                
                csv.push(row.join(','));
            }
            
            // Create and download CSV file
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', 'feedback_report_' + new Date().toISOString().slice(0,10) + '.csv');
            link.click();
        }
        
        // Chart data and initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Laboratory ratings chart
            const labCtx = document.getElementById('labRatingChart').getContext('2d');
            const labChart = new Chart(labCtx, {
                type: 'bar',
                data: {
                    labels: <?php 
                        $labRatingQuery = "SELECT s.laboratory, AVG(r.rating) as avg_rating 
                                           FROM sit_in_ratings r 
                                           JOIN curr_sit_in s ON r.sit_in_id = s.sit_in_id 
                                           GROUP BY s.laboratory
                                           ORDER BY avg_rating DESC";
                        $labRatingResult = $conn->query($labRatingQuery);
                        $labLabels = [];
                        $labData = [];
                        while ($row = $labRatingResult->fetch_assoc()) {
                            $labLabels[] = $row['laboratory'];
                            $labData[] = number_format($row['avg_rating'], 2);
                        }
                        echo json_encode($labLabels);
                    ?>,
                    datasets: [{
                        label: 'Average Rating',
                        data: <?php echo json_encode($labData); ?>,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
            
            // Rating trends chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: <?php 
                        $trendQuery = "SELECT DATE_FORMAT(r.created_at, '%Y-%m-%d') as date, AVG(r.rating) as avg_rating
                                       FROM sit_in_ratings r
                                       GROUP BY DATE_FORMAT(r.created_at, '%Y-%m-%d')
                                       ORDER BY date ASC
                                       LIMIT 14";
                        $trendResult = $conn->query($trendQuery);
                        $trendLabels = [];
                        $trendData = [];
                        while ($row = $trendResult->fetch_assoc()) {
                            $trendLabels[] = date('M d', strtotime($row['date']));
                            $trendData[] = number_format($row['avg_rating'], 2);
                        }
                        echo json_encode($trendLabels);
                    ?>,
                    datasets: [{
                        label: 'Daily Average Rating',
                        data: <?php echo json_encode($trendData); ?>,
                        fill: false,
                        borderColor: 'rgba(245, 158, 11, 1)',
                        tension: 0.1,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>