<?php include("navbar_admin.php"); ?>
<?php include("./conn_back/feedback_process.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Dashboard | CCS Lab System</title>
    <?php include("icon.php"); ?>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <style>
    /* Custom scrollbar styling */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        transition: background 0.3s ease;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    #feedbackTable tbody tr {
        height: 72px; /* Makes each row exactly 72px tall */
        transition: all 0.2s ease;
    }

    /* Row animation on scroll */
    .overflow-y-auto {
        scroll-behavior: smooth;
    }

    /* Row hover effect with subtle animation */
    #feedbackTable tbody tr:hover {
        background-color: rgba(243, 244, 246, 0.7);
        transform: translateX(2px);
    }

    /* Add a subtle fade-in animation for rows as they come into view */
    @keyframes fadeInRow {
        from { opacity: 0.4; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Apply animation to rows when scrolled into view */
    .table-row-animated {
        animation: fadeInRow 0.3s ease forwards;
    }

    /* Add a nice scrolling highlight effect */
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #cbd5e1, #93c5fd, #cbd5e1);
        background-size: 100% 300%;
        animation: scrollGradient 3s ease infinite;
    }

    @keyframes scrollGradient {
        0% { background-position: 0% 0%; }
        50% { background-position: 0% 100%; }
        100% { background-position: 0% 0%; }
    }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Student Feedback Dashboard</h1>
                <p class="text-gray-500 mt-1">Analyze laboratory usage satisfaction and student insights</p>
            </div>
            <button onclick="exportToCSV()" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition duration-200 gap-2 shadow-sm">
                <i class="fas fa-file-export"></i> Export Data
            </button>
        </div>
        
        <!-- Dashboard Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Average Rating -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Average Rating</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($stats['avg_rating'], 1); ?></h2>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-star text-blue-500 text-xl"></i>
                    </div>
                </div>
                <div class="flex items-center mt-4">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?php echo $i <= round($stats['avg_rating']) ? 'text-yellow-400' : 'text-gray-300'; ?> mr-1"></i>
                    <?php endfor; ?>
                    <span class="text-sm text-gray-500 ml-2">from <?php echo $stats['total_ratings']; ?> ratings</span>
                </div>
            </div>
            
            <!-- Highest Rated Lab -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-emerald-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Highest Rated Lab</p>
                        <h2 class="text-xl font-bold text-gray-800 mt-2"><?php echo $bestLab['lab'] ?? 'N/A'; ?></h2>
                    </div>
                    <div class="bg-emerald-50 p-3 rounded-lg">
                        <i class="fas fa-trophy text-emerald-500 text-xl"></i>
                    </div>
                </div>
                <div class="flex items-center mt-4">
                    <?php if(isset($bestLab['rating'])): ?>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= round($bestLab['rating']) ? 'text-yellow-400' : 'text-gray-300'; ?> mr-1"></i>
                        <?php endfor; ?>
                        <span class="text-sm text-gray-500 ml-2"><?php echo number_format($bestLab['rating'], 1); ?> avg</span>
                    <?php else: ?>
                        <span class="text-sm text-gray-500">No ratings yet</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Feedback -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Recent Feedback</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2"><?php echo $result->num_rows; ?></h2>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <i class="fas fa-comments text-purple-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-full">
                        <?php echo $stats['five_star']; ?> 5★
                    </span>
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                        <?php echo $stats['four_star']; ?> 4★
                    </span>
                    <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full">
                        <?php echo $stats['three_star']; ?> 3★
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Rating Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Rating Distribution</h2>
                    <div class="text-xs font-medium px-2 py-1 bg-blue-50 text-blue-700 rounded-full"><?php echo $stats['total_ratings']; ?> total</div>
                </div>
                <div class="p-6 space-y-2">
                    <!-- 5 stars -->
                    <div class="flex items-center">
                        <div class="w-8 text-sm font-medium text-gray-700">5★</div>
                        <div class="flex-grow mx-2">
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-yellow-400" style="width: <?php echo $starPercentages['five']; ?>%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm text-gray-500"><?php echo $stats['five_star']; ?></div>
                    </div>
                    
                    <!-- 4 stars -->
                    <div class="flex items-center">
                        <div class="w-8 text-sm font-medium text-gray-700">4★</div>
                        <div class="flex-grow mx-2">
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-yellow-400" style="width: <?php echo $starPercentages['four']; ?>%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm text-gray-500"><?php echo $stats['four_star']; ?></div>
                    </div>
                    
                    <!-- 3 stars -->
                    <div class="flex items-center">
                        <div class="w-8 text-sm font-medium text-gray-700">3★</div>
                        <div class="flex-grow mx-2">
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-yellow-400" style="width: <?php echo $starPercentages['three']; ?>%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm text-gray-500"><?php echo $stats['three_star']; ?></div>
                    </div>
                    
                    <!-- 2 stars -->
                    <div class="flex items-center">
                        <div class="w-8 text-sm font-medium text-gray-700">2★</div>
                        <div class="flex-grow mx-2">
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-yellow-400" style="width: <?php echo $starPercentages['two']; ?>%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm text-gray-500"><?php echo $stats['two_star']; ?></div>
                    </div>
                    
                    <!-- 1 star -->
                    <div class="flex items-center">
                        <div class="w-8 text-sm font-medium text-gray-700">1★</div>
                        <div class="flex-grow mx-2">
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-yellow-400" style="width: <?php echo $starPercentages['one']; ?>%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm text-gray-500"><?php echo $stats['one_star']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Lab Comparison Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800">Laboratory Comparison</h2>
                </div>
                <div class="p-4 h-60">
                    <canvas id="labRatingChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Filter Feedback</h2>
            </div>
            <div class="p-6">
                <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratory</label>
                        <select name="laboratory" id="laboratory" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">All Laboratories</option>
                            <?php foreach($laboratories as $lab): ?>
                                <option value="<?php echo $lab; ?>" <?php echo ($labFilter == $lab) ? 'selected' : ''; ?>>
                                    <?php echo $lab; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <select name="rating" id="rating" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                            <option value="0">All Ratings</option>
                            <option value="5" <?php echo ($ratingFilter == 5) ? 'selected' : ''; ?>>5 Stars</option>
                            <option value="4" <?php echo ($ratingFilter == 4) ? 'selected' : ''; ?>>4 Stars</option>
                            <option value="3" <?php echo ($ratingFilter == 3) ? 'selected' : ''; ?>>3 Stars</option>
                            <option value="2" <?php echo ($ratingFilter == 2) ? 'selected' : ''; ?>>2 Stars</option>
                            <option value="1" <?php echo ($ratingFilter == 1) ? 'selected' : ''; ?>>1 Star</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <input type="text" id="date_range" name="date_range" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" value="<?php echo $dateFilter; ?>" placeholder="Select date range">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-filter mr-2"></i> Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Feedback Results -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-800">Feedback Results</h2>
                    <p class="text-sm text-gray-500 mt-1">Showing <?php echo $result->num_rows; ?> entries</p>
                </div>
                <div class="relative">
                    <input type="text" id="tableSearch" placeholder="Search feedback..." class="border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-y-auto" style="max-height: 450px; min-height: 300px;">
                    <table class="min-w-full divide-y divide-gray-200" id="feedbackTable">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-9 w-9">
                                                <?php $profileImg = !empty($row['profileImg']) ? '../' . $row['profileImg'] : '../images/person.jpg'; ?>
                                                <img class="h-9 w-9 rounded-full object-cover" src="<?php echo $profileImg; ?>" alt="">
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['idno']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 inline-flex text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            <?php echo htmlspecialchars($row['laboratory']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $row['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?> text-sm"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="text-sm text-gray-900">
                                            <?php if (!empty($row['feedback'])): ?>
                                                <p class="truncate" title="<?php echo htmlspecialchars($row['feedback']); ?>"><?php echo htmlspecialchars($row['feedback']); ?></p>
                                                <button class="text-blue-600 hover:text-blue-800 text-xs mt-1" 
                                                        onclick="showFeedback('<?php echo addslashes(htmlspecialchars($row['feedback'])); ?>', '<?php echo addslashes(htmlspecialchars($row['firstname'] . ' ' . $row['lastname'])); ?>')">
                                                    Read more
                                                </button>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic">No written feedback</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No feedback found</h3>
                    <p class="text-gray-500 mt-2">Try adjusting your filters to see more results</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800" id="modal-student-name"></h3>
                <button onclick="closeFeedback()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <p id="modal-feedback-text" class="text-gray-800"></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-b-lg flex justify-end">
                <button onclick="closeFeedback()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Close</button>
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
        
        // Table search functionality
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('feedbackTable');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Feedback modal
        function showFeedback(feedback, studentName) {
            const modal = document.getElementById('feedbackModal');
            const modalContent = document.getElementById('modalContent');
            
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('modal-feedback-text').textContent = feedback;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeFeedback() {
            const modal = document.getElementById('feedbackModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
        
        document.getElementById('feedbackModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFeedback();
            }
        });
        
        // Export to CSV function
        function exportToCSV() {
            const table = document.getElementById('feedbackTable');
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/,/g, ';');
                    
                    if (i > 0 && j === 2) {
                        const stars = cols[j].querySelectorAll('.fa-star.text-yellow-400').length;
                        text = stars + ' Stars';
                    }
                    
                    row.push('"' + text + '"');
                }
                
                csv.push(row.join(','));
            }
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', 'feedback_report_' + new Date().toISOString().slice(0,10) + '.csv');
            link.click();
        }
        
        // Chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            const labCtx = document.getElementById('labRatingChart').getContext('2d');
            new Chart(labCtx, {
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
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)', 
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(245, 158, 11, 0.7)'
                        ],
                        borderRadius: 4,
                        maxBarThickness: 35
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
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
        
        // Add row animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.overflow-y-auto');
            const tableRows = document.querySelectorAll('#feedbackTable tbody tr');
            
            if (tableContainer && tableRows.length) {
                // Apply animation to visible rows initially
                applyAnimationToVisibleRows();
                
                // Apply animation on scroll
                tableContainer.addEventListener('scroll', function() {
                    applyAnimationToVisibleRows();
                });
                
                function applyAnimationToVisibleRows() {
                    const containerRect = tableContainer.getBoundingClientRect();
                    
                    tableRows.forEach(row => {
                        const rowRect = row.getBoundingClientRect();
                        const isVisible = rowRect.top <= containerRect.bottom && 
                                        rowRect.bottom >= containerRect.top;
                        
                        if (isVisible && !row.classList.contains('table-row-animated')) {
                            row.classList.add('table-row-animated');
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>