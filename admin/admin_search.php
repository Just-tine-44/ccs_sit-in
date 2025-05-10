
<?php include("./conn_back/search_process.php"); ?>
<?php include("navbar_admin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("icon.php"); ?>
    <title>Student Search - Admin Panel</title>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 md:p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Student Search</h1>
                <p class="text-gray-500">Find students and manage their sit-in sessions</p>
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
        
        <!-- Search Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="max-w-3xl mx-auto">
                <form id="searchForm" method="GET" action="" class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-1">
                            <label for="search_term" class="block text-sm font-medium text-gray-700 mb-1">Student ID or Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="search_term" 
                                    name="search_term" 
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Enter student ID or name..."
                                    value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>"
                                    autofocus
                                >
                            </div>
                        </div>
                        <div>
                            <button 
                                type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-300 flex items-center"
                            >
                                Search
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="flex items-center border-b border-gray-200 pb-2 mb-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <p class="text-gray-600 text-sm">
                        You can search by student ID (e.g. "22669944") or by student name (e.g. "John Smith").
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <!-- Recent Searches section in admin_search.php -->
                    <?php if (!empty($recentSearches)): ?>
                        <div class="flex flex-wrap gap-3 mt-4">
                            <?php foreach($recentSearches as $search): ?>
                                <a href="?search_term=<?= urlencode($search['value']) ?>" 
                                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm cursor-pointer hover:bg-gray-200 transition-colors duration-200 flex items-center">
                                    <i class="fas fa-history mr-1 text-gray-500"></i>
                                    <?= $search['type'] === 'id' ? 'ID: ' : '' ?><?= htmlspecialchars($search['value']) ?>
                                </a>
                            <?php endforeach; ?>
                            
                            <?php if(count($recentSearches) > 0): ?>
                                <button onclick="clearRecentSearches()" class="px-3 py-1 text-sm text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt mr-1"></i> Clear
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($searchPerformed): ?>
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $student): ?>
                    <!-- Results Section -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 animate__animated animate__fadeIn">
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-800 mb-1">Student Information</h2>
                            <p class="text-gray-500 text-sm">Complete details for selected student</p>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Student Profile -->
                            <div class="md:w-1/3">
                                <div class="bg-gray-50 rounded-xl p-6 text-center">
                                    <div class="w-24 h-24 rounded-full bg-blue-100 mx-auto mb-4 overflow-hidden">
                                    <?php
                                    // Clean, production-ready image path logic
                                    if (!empty($student['profileImg'])) {
                                        // Check if the stored path already contains "uploadimg/"
                                        if (strpos($student['profileImg'], 'uploadimg/') === 0) {
                                            // Path already includes uploadimg/ folder - just add ../ to go up one directory
                                            $profileImg = '../' . $student['profileImg'];
                                        } 
                                        else if (strpos($student['profileImg'], '../') === 0 || 
                                                strpos($student['profileImg'], '/') === 0 || 
                                                strpos($student['profileImg'], 'http') === 0) {
                                            // Path already has protocol or root indicators
                                            $profileImg = $student['profileImg'];
                                        } 
                                        else {
                                            // No special path indicators, add the full path
                                            $profileImg = '../uploadimg/' . $student['profileImg'];
                                        }
                                    } else {
                                        // Default image if no profile image is set
                                        $profileImg = '../images/person.jpg';
                                    }
                                    ?>
                                    <img src="<?php echo $profileImg; ?>" alt="Student" class="w-full h-full object-cover" onerror="this.src='../images/person.jpg';">
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></h3>
                                    <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($student['idno']); ?></p>
                                    <div class="flex items-center justify-center mt-2">
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-700 text-green-700 dark:text-white rounded-full text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1"></i> Active Student
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-gray-600 text-sm">Remaining Sessions:</span>
                                            <span class="font-bold text-gray-800"><?php echo $student['remaining_sessions']; ?>/30</span>
                                        </div>
                                        <?php 
                                            $sessionPercentage = ($student['remaining_sessions'] / 30) * 100;
                                            $barColor = $sessionPercentage > 50 ? 'bg-green-600' : ($sessionPercentage > 20 ? 'bg-yellow-500' : 'bg-red-600');
                                        ?>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="<?php echo $barColor; ?> h-2.5 rounded-full" style="width: <?php echo $sessionPercentage; ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Student Details -->
                            <div class="md:w-2/3">
                                <div class="grid md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-500 mb-1">Course</p>
                                        <p class="font-medium"><?php echo htmlspecialchars($student['course']); ?></p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-500 mb-1">Year Level</p>
                                        <p class="font-medium"><?php echo htmlspecialchars($student['level']); ?></p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-500 mb-1">Email</p>
                                        <p class="font-medium"><?php echo htmlspecialchars($student['email']); ?></p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-500 mb-1">Last Sit-in</p>
                                        <p class="font-medium">
                                            <?php 
                                                if ($student['last_sit_in']) {
                                                    $lastSitIn = new DateTime($student['last_sit_in']);
                                                    $now = new DateTime();
                                                    $diff = $now->diff($lastSitIn);
                                                    $days = $diff->days;
                                                    
                                                    echo date('M j, Y', strtotime($student['last_sit_in']));
                                                    echo " ({$days} " . ($days == 1 ? "day" : "days") . " ago)";
                                                } else {
                                                    echo "No previous sessions";
                                                }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Sit-in Registration Form -->
                                <div class="bg-blue-50 rounded-xl p-5">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-chair text-blue-500 mr-2"></i> 
                                        New Sit-in Session
                                    </h3>
                                    
                                    <form id="sitInForm" method="POST" action="" class="space-y-4">
                                        <input type="hidden" name="user_id" value="<?php echo $student['id']; ?>">
                                        <input type="hidden" name="search_term" value="<?php echo htmlspecialchars($_GET['search_term']); ?>">
                                        
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1" for="purpose">Purpose</label>
                                                <select id="purpose" name="purpose" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                    <option value="">Select Purpose</option>
                                                    <option value="C Programming">C Programming</option>
                                                    <option value="Java Programming">Java Programming</option>
                                                    <option value="C#">C#</option>
                                                    <option value="PHP">PHP</option>
                                                    <option value="ASP.Net">ASP.Net</option>
                                                    <option value="Database">Database</option>
                                                    <option value="Digital Logic & Design">Digital Logic & Design</option>
                                                    <option value="Embedded System % IOT">Embedded System % IOT</option>
                                                    <option value="Python Programming">Python Programming</option>
                                                    <option value="Systems Integration & Architecture">Systems Integration & Architecture</option>
                                                    <option value="Computer Application">Computer Application</option>
                                                    <option value="Web Design & Development">Web Design & Development</option>
                                                    <option value="Project Management">Project Management</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1" for="laboratory">Laboratory</label>
                                                <select id="laboratory" name="laboratory" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                    <option value="">Select Laboratory</option>
                                                    <?php foreach ($laboratories as $lab): ?>
                                                        <option value="<?php echo $lab; ?>">
                                                            <?php echo $lab; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" name="register_sit_in" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-300 flex items-center">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Time - In
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- No Results Message -->
                <div id="noResultsMessage" class="bg-white rounded-xl shadow-sm p-6 animate__animated animate__fadeIn">
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-search text-gray-400 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">No Students Found</h2>
                        <p class="text-gray-500 mb-6">We couldn't find any students matching your search criteria.</p>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="document.getElementById('search_term').focus()">
                            Try Another Search
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Any additional client-side scripts you might need
            <?php if (!$searchPerformed): ?>
            document.getElementById('search_term').focus();
            <?php endif; ?>
        });

        function clearRecentSearches() {
            // Use fetch API to call a PHP script that clears recent searches
            fetch('./conn_back/clear_recent_searches.php', { method: 'POST' })
                .then(response => {
                    if (response.ok) {
                        // Reload the page to reflect changes
                        window.location.reload();
                    }
                });
        }
    </script>
</body>
</html>