<?php
// filepath: c:\xampp\htdocs\login\user_resources.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
$profileImg = isset($user['profileImg']) ? $user['profileImg'] : 'images/person.jpg';

// Get user's year level and course for filtering resources
$userYearLevel = $user['level'];
$userCourse = $user['course'];

include('conn/dbcon.php'); 

// Debug information
$debug = [
    'userYearLevel' => $userYearLevel,
    'userCourse' => $userCourse
];

// Fetch lab resources based on user's year level and course
// Handle different year level formats (e.g., "1st Year" vs "1")
$resources = [];

// Extract just the number from year level if it contains text
$yearNumber = preg_replace('/[^0-9]/', '', $userYearLevel);
$yearWithSuffix = $yearNumber . (($yearNumber == '1') ? 'st Year' : (($yearNumber == '2') ? 'nd Year' : (($yearNumber == '3') ? 'rd Year' : 'th Year')));

// Make the query more flexible to show more resources to users
$query = "SELECT * FROM lab_resources WHERE 
          (year_level = ? OR year_level = ? OR year_level = 'All Years' OR year_level IS NULL OR year_level = '') AND 
          (course = ? OR course = 'All Courses' OR course IS NULL OR course = '') AND 
          is_active = 1 
          ORDER BY upload_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $userYearLevel, $yearWithSuffix, $userCourse);
$stmt->execute();
$result = $stmt->get_result();

$debug['resourceCount'] = $result->num_rows;
$debug['query'] = $query;
$debug['yearWithSuffix'] = $yearWithSuffix;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
}
$stmt->close();

// Check if the course column exists in the lab_resources table
$courseColumnExists = false;
$columnsQuery = "SHOW COLUMNS FROM lab_resources LIKE 'course'";
$columnsResult = $conn->query($columnsQuery);
if ($columnsResult && $columnsResult->num_rows > 0) {
    $courseColumnExists = true;
}
$debug['courseColumnExists'] = $courseColumnExists;

// Let's fetch all resources for debugging purposes
$allResources = [];
$allQuery = "SELECT * FROM lab_resources WHERE is_active = 1";
$allResult = $conn->query($allQuery);
if ($allResult && $allResult->num_rows > 0) {
    while ($row = $allResult->fetch_assoc()) {
        $allResources[] = $row;
    }
}
$debug['allResourcesCount'] = count($allResources);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Resources</title>
    <link rel="icon" type="image/png" href="images/wbccs.png">
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Line clamp for description text */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .filter-badge {
            transition: all 0.2s ease;
        }
        .filter-badge:hover {
            transform: translateY(-2px);
        }
        .resource-card {
            transition: all 0.3s ease;
        }
        .resource-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    
    <div class="container mx-auto p-4 max-w-6xl">
        <div class="mb-6 bg-white p-6 rounded-xl shadow-md">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-book-reader text-blue-600 mr-3"></i>
                Lab Resources & Materials
            </h1>
            <p class="text-gray-600 mt-2">
                Access course materials, lab guides, and other resources for 
                <span class="font-medium text-blue-600"><?php echo htmlspecialchars($userCourse); ?></span> 
                students in 
                <span class="font-medium text-blue-600"><?php echo htmlspecialchars($userYearLevel); ?></span>.
            </p>
            
            <?php if (isset($_GET['debug'])): ?>
                <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                    <h3 class="font-semibold mb-2">Debug Information:</h3>
                    <pre class="text-xs overflow-auto"><?php echo htmlspecialchars(json_encode($debug, JSON_PRETTY_PRINT)); ?></pre>
                    
                    <h4 class="font-semibold mt-3 mb-1">All Available Resources:</h4>
                    <ul class="text-xs">
                        <?php foreach ($allResources as $res): ?>
                            <li class="mb-1 p-1 bg-white">
                                <?php echo htmlspecialchars($res['title']); ?> - 
                                Year: <?php echo htmlspecialchars($res['year_level'] ?: 'Not set'); ?>, 
                                Course: <?php echo $courseColumnExists ? htmlspecialchars($res['course'] ?: 'Not set') : 'Column not found'; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- Advanced Filtering Section -->
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h2 class="text-white font-medium flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Resource Filters
                </h2>
            </div>
            <div class="p-6">
                <!-- Type Filter -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Resource Type</h3>
                    <div class="flex flex-wrap gap-2">
                        <button class="type-filter active px-3 py-1 rounded-full text-sm bg-blue-600 text-white filter-badge" data-type="all">
                            All Types
                        </button>
                        <button class="type-filter px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 hover:bg-blue-100 filter-badge" data-type="document">
                            <i class="fas fa-file-alt mr-1"></i> Documents
                        </button>
                        <button class="type-filter px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 hover:bg-blue-100 filter-badge" data-type="video">
                            <i class="fas fa-video mr-1"></i> Videos
                        </button>
                        <button class="type-filter px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 hover:bg-blue-100 filter-badge" data-type="link">
                            <i class="fas fa-link mr-1"></i> Links
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($resources)): ?>
                <div class="col-span-full bg-white rounded-xl shadow-md p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-blue-50 p-4 rounded-full mb-4">
                            <i class="fas fa-file-alt text-blue-300 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-800 mb-2">No Resources Available</h3>
                        <p class="text-gray-600">
                            There are currently no resources available for your course and year level. 
                            Check back later as new materials may be added soon.
                        </p>
                        <a href="?debug=1" class="mt-4 text-xs text-blue-500 hover:underline">Check system information</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($resources as $resource): ?>
                    <?php
                        // Determine icon based on resource type
                        $resourceIcon = 'fa-file-alt';
                        $resourceBg = 'bg-blue-100';
                        $resourceColor = 'text-blue-600';
                        
                        if ($resource['resource_type'] == 'video') {
                            $resourceIcon = 'fa-video';
                            $resourceBg = 'bg-red-100';
                            $resourceColor = 'text-red-600';
                        } elseif ($resource['resource_type'] == 'link') {
                            $resourceIcon = 'fa-link';
                            $resourceBg = 'bg-green-100';
                            $resourceColor = 'text-green-600';
                        }
                    ?>
                    <div class="resource-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" 
                         data-type="<?php echo htmlspecialchars($resource['resource_type']); ?>">
                        <div class="p-5">
                            <div class="flex items-start">
                                <div class="<?php echo $resourceBg; ?> p-3 rounded-lg mr-4">
                                    <i class="fas <?php echo $resourceIcon; ?> <?php echo $resourceColor; ?> text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800 mb-1"><?php echo htmlspecialchars($resource['title']); ?></h3>
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                        <?php echo htmlspecialchars($resource['description']); ?>
                                    </p>
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-user-graduate mr-1"></i> <?php echo htmlspecialchars($resource['year_level']); ?>
                                        </span>
                                        <?php if ($courseColumnExists && !empty($resource['course'])): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-graduation-cap mr-1"></i> <?php echo htmlspecialchars($resource['course']); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs text-gray-500">
                                            <i class="far fa-calendar-alt mr-1"></i> 
                                            <?php echo date('M d, Y', strtotime($resource['upload_date'])); ?>
                                        </div>
                                        <div>
                                            <?php if (!empty($resource['file_path'])): ?>
                                                <a href="<?php echo htmlspecialchars($resource['file_path']); ?>" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($resource['link_url'])): ?>
                                                <a href="<?php echo htmlspecialchars($resource['link_url']); ?>" target="_blank" class="inline-flex items-center text-green-600 hover:text-green-800 text-sm font-medium ml-3">
                                                    <i class="fas fa-external-link-alt mr-1"></i> Open
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Type filtering functionality
            const typeFilterButtons = document.querySelectorAll('.type-filter');
            const resourceCards = document.querySelectorAll('.resource-card');
            
            // Currently active filter
            let activeTypeFilter = 'all';
            
            // Apply filters function
            function applyFilters() {
                resourceCards.forEach(card => {
                    const cardType = card.getAttribute('data-type');
                    
                    const typeMatch = activeTypeFilter === 'all' || cardType === activeTypeFilter;
                    
                    if (typeMatch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            // Type filter buttons
            typeFilterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button style
                    typeFilterButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-600', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-blue-100');
                    });
                    
                    this.classList.add('active', 'bg-blue-600', 'text-white');
                    this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-blue-100');
                    
                    // Set active filter
                    activeTypeFilter = this.getAttribute('data-type');
                    
                    // Apply filters
                    applyFilters();
                });
            });
        });
    </script>
</body>
</html>