<?php include("navbar_admin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Search - Admin Panel</title>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        
        <!-- Search Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="max-w-3xl mx-auto">
                <form id="searchForm" class="mb-6">
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
                        You can search by student ID (e.g. "2023-1234") or by student name (e.g. "John Smith").
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm cursor-pointer hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-history mr-1 text-gray-500"></i>
                        Recent: John Doe
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm cursor-pointer hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-history mr-1 text-gray-500"></i>
                        Recent: Maria Santos
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm cursor-pointer hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-history mr-1 text-gray-500"></i>
                        Recent: 2023-1234
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Results Section (Hidden by default, shown after search) -->
        <div id="resultsSection" class="bg-white rounded-xl shadow-sm p-6 hidden">
            <div class="mb-4 pb-4 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-1">Student Information</h2>
                <p class="text-gray-500 text-sm">Complete details for selected student</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Student Profile -->
                <div class="md:w-1/3">
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="w-24 h-24 rounded-full bg-blue-100 mx-auto mb-4 overflow-hidden">
                            <img src="../images/person.jpg" alt="Student" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">John Doe</h3>
                        <p class="text-blue-600 font-medium">2023-12345</p>
                        <div class="flex items-center justify-center mt-2">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                Active Student
                            </span>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 text-sm">Remaining Sessions:</span>
                                <span class="font-bold text-gray-800">24/30</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Student Details -->
                <div class="md:w-2/3">
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Course</p>
                            <p class="font-medium">Bachelor of Science in Information Technology</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Year Level</p>
                            <p class="font-medium">3rd Year</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="font-medium">johndoe@example.com</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Last Sit-in</p>
                            <p class="font-medium">March 8, 2025 (3 days ago)</p>
                        </div>
                    </div>
                    
                    <!-- Sit-in Registration Form -->
                    <div class="bg-blue-50 rounded-xl p-5">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chair text-blue-500 mr-2"></i> 
                            Register New Sit-in Session
                        </h3>
                        
                        <form id="sitInForm" class="space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="purpose">Purpose</label>
                                    <select id="purpose" name="purpose" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Purpose</option>
                                        <option value="research">Research</option>
                                        <option value="assignment">Assignment</option>
                                        <option value="project">Project</option>
                                        <option value="study">Study</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="laboratory">Laboratory</label>
                                    <select id="laboratory" name="laboratory" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Laboratory</option>
                                        <option value="lab1">Computer Lab 1 (Available: 15 PCs)</option>
                                        <option value="lab2">Computer Lab 2 (Available: 8 PCs)</option>
                                        <option value="lab3">Computer Lab 3 (Available: 20 PCs)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-300 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Register Sit-in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- No Results Message (Hidden by default, shown when no results found) -->
        <div id="noResultsMessage" class="bg-white rounded-xl shadow-sm p-6 hidden">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const resultsSection = document.getElementById('resultsSection');
            const noResultsMessage = document.getElementById('noResultsMessage');
            
            // Demo functionality - just for UI testing
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = document.getElementById('search_term').value.trim();
                
                if (searchTerm) {
                    // Show results section (in a real app, you'd fetch data from the server)
                    resultsSection.classList.remove('hidden');
                    noResultsMessage.classList.add('hidden');
                    
                    // Scroll to results
                    resultsSection.scrollIntoView({ behavior: 'smooth' });
                } else {
                    resultsSection.classList.add('hidden');
                    noResultsMessage.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>

<!-- In admin search a functionality, in here is the admin will search a registered student, 
after it search, there will be pop-up of student_id, Full Name, Course and Year reaming 
session, also a purpose and laboratory (available), 
make it beautiful and modern using tailwind (which is already i have in local) -->