<?php 
    include('./conn_back/resources_process.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lab Resources | Admin</title>
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="admin_home.php" class="group flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                    <div class="p-1.5 rounded-full bg-gray-100 group-hover:bg-blue-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="font-medium">Back to Dashboard</span>
                </a>
                <div class="hidden md:block text-sm text-gray-600">
                    Lab Resources Management
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-book-reader text-blue-600 mr-3"></i>
                    Lab Resources Management
                </h1>
                <p class="text-gray-600 mt-1">Provide students with quality learning materials and resources</p>
            </div>
            
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <div class="relative inline-block text-left">
                    <button id="filterBtn" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2 text-gray-500"></i>
                        Filter
                    </button>
                    <div id="filterMenu" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-700">Filter by Year Level</p>
                            </div>
                            <button class="filter-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left active" data-year="all">All Years</button>
                            <button class="filter-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-year="1">1st Year</button>
                            <button class="filter-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-year="2">2nd Year</button>
                            <button class="filter-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-year="3">3rd Year</button>
                            <button class="filter-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-year="4">4th Year</button>
                        </div>
                    </div>
                </div>
                
                <button id="addResourceBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Add Resource
                </button>
            </div>
        </div>
        
        <!-- Success/Error Messages -->
        <div id="alertMessages">
            <?php if (isset($_SESSION['success_message'])): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '<?php echo $_SESSION['success_message']; ?>',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                </script>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '<?php echo $_SESSION['error_message']; ?>',
                        showConfirmButton: true
                    });
                </script>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Resources Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Resources</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo count($resources); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Document Count Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <i class="fas fa-file-pdf text-indigo-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Documents</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo count(array_filter($resources, function($r) { return $r['resource_type'] == 'document'; })); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Video Count Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <i class="fas fa-video text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Videos</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo count(array_filter($resources, function($r) { return $r['resource_type'] == 'video'; })); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Links Count Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-link text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">External Links</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo count(array_filter($resources, function($r) { return $r['resource_type'] == 'link'; })); ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- Add Resource Form (Hidden by default) -->
        <div id="resourceForm" class="bg-white shadow-lg rounded-lg mb-6 hidden">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-cloud-upload-alt text-blue-500 mr-2"></i>
                    Upload New Resource
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Fill out the form below to add a new resource for students
                </p>
            </div>
            
            <div class="px-4 py-5 sm:p-6">
                <form action="conn_back/resources_process.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Resource Title</label>
                            <div class="mt-1">
                                <input type="text" id="title" name="title" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="resource_type" class="block text-sm font-medium text-gray-700">Resource Type</label>
                            <div class="mt-1">
                                <select id="resource_type" name="resource_type" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="document">Document</option>
                                    <option value="video">Video</option>
                                    <option value="link">External Link</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Briefly describe what this resource contains and how it will help students.</p>
                    </div>
                    
                    <div>
                        <label for="year_level" class="block text-sm font-medium text-gray-700">Year Level</label>
                        <div class="mt-1">
                            <select id="year_level" name="year_level" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                <option value="">-- Select Year Level --</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="file_upload_section">
                        <label for="resource_file" class="block text-sm font-medium text-gray-700">Upload File</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="resource_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="resource_file" name="resource_file" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, DOCX, MP4, etc. up to 10MB</p>
                            </div>
                        </div>
                        <p id="selected_file" class="mt-2 text-sm text-gray-500"></p>
                    </div>
                    
                    <div id="link_section" class="hidden">
                        <label for="link_url" class="block text-sm font-medium text-gray-700">External URL</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" id="link_url" name="link_url" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300" placeholder="https://example.com/resource">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Enter the full URL including https:// or http://</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-5 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" name="add_resource" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-upload mr-2"></i> Upload Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Resources Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 sm:px-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-white">
                        Lab Resources
                    </h3>
                    <span id="resourceCount" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <?php echo count($resources); ?> total
                    </span>
                </div>
            </div>
            
            <?php if (empty($resources)): ?>
                <div class="px-4 py-16 sm:px-6 text-center">
                    <div class="flex flex-col items-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                            <i class="fas fa-folder-open text-blue-400 text-2xl"></i>
                        </div>
                        <h3 class="mt-3 text-lg font-medium text-gray-900">No resources yet</h3>
                        <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                            You haven't added any learning resources yet. Get started by clicking the "Add Resource" button above.
                        </p>
                        <div class="mt-6">
                            <button id="emptyStateBtn" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-2"></i>
                                Add Your First Resource
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Resource Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Year Level
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Uploaded
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($resources as $resource): ?>
                                <tr class="resource-row hover:bg-gray-50" data-year="<?php echo $resource['year_level']; ?>">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <?php
                                                // Icon based on resource type
                                                $icon = 'fa-file-alt text-blue-500';
                                                $bgColor = 'bg-blue-100';
                                                
                                                if ($resource['resource_type'] == 'video') {
                                                    $icon = 'fa-video text-red-500';
                                                    $bgColor = 'bg-red-100';
                                                } elseif ($resource['resource_type'] == 'link') {
                                                    $icon = 'fa-link text-green-500';
                                                    $bgColor = 'bg-green-100';
                                                }
                                            ?>
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full <?php echo $bgColor; ?> flex items-center justify-center">
                                                <i class="fas <?php echo $icon; ?>"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($resource['title']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500 max-w-xs truncate">
                                                    <?php echo htmlspecialchars($resource['description']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php 
                                                if ($resource['resource_type'] == 'document') echo 'bg-blue-100 text-blue-800';
                                                elseif ($resource['resource_type'] == 'video') echo 'bg-red-100 text-red-800';
                                                else echo 'bg-green-100 text-green-800';
                                            ?>">
                                            <?php echo ucfirst($resource['resource_type']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo $resource['year_level']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo date('M d, Y', strtotime($resource['upload_date'])); ?></div>
                                        <div class="text-xs text-gray-500">by <?php echo htmlspecialchars($resource['uploaded_by']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <?php if (!empty($resource['file_path'])): ?>
                                                <a href="../<?php echo htmlspecialchars($resource['file_path']); ?>" target="_blank" class="text-blue-600 hover:text-blue-900 transition-colors" title="View File">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($resource['link_url'])): ?>
                                                <a href="<?php echo htmlspecialchars($resource['link_url']); ?>" target="_blank" class="text-green-600 hover:text-green-900 transition-colors" title="Open Link">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <button class="text-red-600 hover:text-red-900 transition-colors delete-resource" data-id="<?php echo $resource['id']; ?>" title="Delete Resource">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle form visibility
            const addResourceBtn = document.getElementById('addResourceBtn');
            const emptyStateBtn = document.getElementById('emptyStateBtn');
            const resourceForm = document.getElementById('resourceForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            // Filter dropdown
            const filterBtn = document.getElementById('filterBtn');
            const filterMenu = document.getElementById('filterMenu');
            const filterOptions = document.querySelectorAll('.filter-option');
            const resourceRows = document.querySelectorAll('.resource-row');
            const resourceCount = document.getElementById('resourceCount');
            
            function showAddResourceForm() {
                resourceForm.classList.remove('hidden');
                // Smooth scroll to form
                resourceForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            if (addResourceBtn) {
                addResourceBtn.addEventListener('click', showAddResourceForm);
            }
            
            if (emptyStateBtn) {
                emptyStateBtn.addEventListener('click', showAddResourceForm);
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    resourceForm.classList.add('hidden');
                });
            }
            
            // Filter dropdown toggle
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    filterMenu.classList.toggle('hidden');
                });
                
                // Close filter dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!filterBtn.contains(event.target) && !filterMenu.contains(event.target)) {
                        filterMenu.classList.add('hidden');
                    }
                });
                
                // Filter options
                filterOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        // Update active state
                        filterOptions.forEach(opt => opt.classList.remove('active', 'bg-blue-50', 'text-blue-700'));
                        this.classList.add('active', 'bg-blue-50', 'text-blue-700');
                        
                        // Get selected year
                        const selectedYear = this.getAttribute('data-year');
                        
                        // Filter table rows
                        let visibleCount = 0;
                        
                        resourceRows.forEach(row => {
                            const yearLevel = row.getAttribute('data-year');
                            
                            if (selectedYear === 'all' || yearLevel === selectedYear) {
                                row.classList.remove('hidden');
                                visibleCount++;
                            } else {
                                row.classList.add('hidden');
                            }
                        });
                        
                        // Update count badge
                        resourceCount.textContent = visibleCount + ' of ' + resourceRows.length;
                        
                        // Close dropdown
                        filterMenu.classList.add('hidden');
                    });
                });
            }
            
            // Handle resource type change
            const resourceType = document.getElementById('resource_type');
            const fileUploadSection = document.getElementById('file_upload_section');
            const linkSection = document.getElementById('link_section');
            
            if (resourceType) {
                resourceType.addEventListener('change', function() {
                    if (this.value === 'link') {
                        fileUploadSection.classList.add('hidden');
                        linkSection.classList.remove('hidden');
                    } else {
                        fileUploadSection.classList.remove('hidden');
                        linkSection.classList.add('hidden');
                    }
                });
            }
            
            // Display selected filename
            const resourceFile = document.getElementById('resource_file');
            const selectedFile = document.getElementById('selected_file');
            
            if (resourceFile && selectedFile) {
                resourceFile.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        selectedFile.innerHTML = '<span class="font-medium">Selected file:</span> ' + 
                            '<span class="text-blue-600">' + this.files[0].name + '</span>';
                    } else {
                        selectedFile.textContent = '';
                    }
                });
            }
            
            // Confirm delete using SweetAlert2
            const deleteButtons = document.querySelectorAll('.delete-resource');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const resourceId = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: 'Delete Resource?',
                        text: "This resource will no longer be available to students. This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel',
                        showClass: {
                            popup: 'animate__animated animate__fadeIn'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `conn_back/resources_process.php?delete=${resourceId}`;
                        }
                    });
                });
            });
            
            // File upload dragover styling
            const dropZone = document.querySelector('#file_upload_section .border-dashed');
            
            if (dropZone) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        this.classList.add('border-blue-400', 'bg-blue-50');
                    });
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        this.classList.remove('border-blue-400', 'bg-blue-50');
                    });
                });
                
                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    if (e.dataTransfer.files.length) {
                        resourceFile.files = e.dataTransfer.files;
                        const event = new Event('change');
                        resourceFile.dispatchEvent(event);
                    }
                });
            }
        });
    </script>
</body>
</html>