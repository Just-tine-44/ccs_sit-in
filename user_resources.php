<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
$profileImg = isset($user['profileImg']) ? $user['profileImg'] : 'images/person.jpg';

// Get user's year level for filtering resources
$userYearLevel = $user['level'];

include('conn/dbcon.php'); 

// Fetch lab resources based on user's year level
// Handle different year level formats (e.g., "1st Year" vs "1")
$resources = [];

// Extract just the number from year level if it contains text
$yearNumber = preg_replace('/[^0-9]/', '', $userYearLevel);

$query = "SELECT * FROM lab_resources WHERE (year_level = ? OR year_level = ?) AND is_active = 1 ORDER BY upload_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $userYearLevel, $yearNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
}
$stmt->close();
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
                Access course materials, lab guides, and other resources for Year Level <?php echo htmlspecialchars($userYearLevel); ?> students.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Resource Type Filter -->
            <div class="col-span-full mb-4">
                <div class="bg-blue-50 p-4 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-filter text-blue-700 mr-2"></i>
                        <span class="font-medium text-blue-800">Filter Resources:</span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="resource-filter active px-3 py-1 rounded-full text-sm bg-blue-600 text-white" data-type="all">
                            All
                        </button>
                        <button class="resource-filter px-3 py-1 rounded-full text-sm bg-white text-gray-700 hover:bg-blue-100" data-type="document">
                            Documents
                        </button>
                        <button class="resource-filter px-3 py-1 rounded-full text-sm bg-white text-gray-700 hover:bg-blue-100" data-type="video">
                            Videos
                        </button>
                        <button class="resource-filter px-3 py-1 rounded-full text-sm bg-white text-gray-700 hover:bg-blue-100" data-type="link">
                            Links
                        </button>
                    </div>
                </div>
            </div>

            <?php if (empty($resources)): ?>
                <div class="col-span-full bg-white rounded-xl shadow-md p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-blue-50 p-4 rounded-full mb-4">
                            <i class="fas fa-file-alt text-blue-300 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-800 mb-2">No Resources Available</h3>
                        <p class="text-gray-600">
                            There are currently no resources available for your year level. 
                            Check back later as new materials may be added soon.
                        </p>
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
                    <div class="resource-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" data-type="<?php echo htmlspecialchars($resource['resource_type']); ?>">
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
        // Resource filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.resource-filter');
            const resourceCards = document.querySelectorAll('.resource-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button style
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-600', 'text-white');
                        btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-blue-100');
                    });
                    
                    this.classList.add('active', 'bg-blue-600', 'text-white');
                    this.classList.remove('bg-white', 'text-gray-700', 'hover:bg-blue-100');
                    
                    const filterType = this.getAttribute('data-type');
                    
                    // Filter resources
                    resourceCards.forEach(card => {
                        if (filterType === 'all' || card.getAttribute('data-type') === filterType) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>

    <style>
        /* Line clamp for description text */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>