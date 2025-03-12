<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History | CCS Lab System</title>
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <!-- Tailwind CSS -->
    <link href="css/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .history-table th {
            position: relative;
        }
        
        .history-table th::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            transform: scaleX(0);
            background: linear-gradient(90deg, #3182ce, #7f9cf5);
            transition: transform 0.3s ease;
        }
        
        .history-table th:hover::after {
            transform: scaleX(1);
        }
        
        .feedback-btn {
            position: relative;
            overflow: hidden;
        }
        
        .feedback-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.2);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
            z-index: 1;
        }
        
        .feedback-btn:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        
        .feedback-btn i,
        .feedback-btn span {
            position: relative;
            z-index: 2;
        }
        
        /* Custom scrollbar for table container */
        .table-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Include navbar at the top of the page -->
    <header class="w-full">
        <?php include 'navbar.php'; ?>
    </header>

    <div class="container mx-auto px-4 py-6">
        <!-- Header with search bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Session History</h1>
                <p class="text-sm text-gray-500 mt-1">View your previous lab sessions and provide feedback</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search input -->
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by lab or purpose..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64 transition-all duration-200" />
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <!-- Filter dropdown -->
                <div class="relative">
                    <select id="filterSelect" class="appearance-none bg-white pl-4 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="semester">This Semester</option>
                    </select>
                    <div class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 transform transition-transform hover:scale-[1.02] duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Sessions</p>
                        <p class="text-2xl font-bold text-gray-800">24</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <i class="fas fa-desktop"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 transform transition-transform hover:scale-[1.02] duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Most Used Lab</p>
                        <p class="text-xl font-bold text-gray-800">Programming Lab</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-gray-500">
                        8 sessions this month
                    </span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 transform transition-transform hover:scale-[1.02] duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Pending Feedback</p>
                        <p class="text-2xl font-bold text-gray-800">3</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="#" class="text-xs text-purple-600 hover:underline">
                        View all pending feedback
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-bold text-gray-700">Recent Sessions</h2>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                    Showing 10 of 24 entries
                </span>
            </div>
            
            <div class="table-container overflow-x-auto">
                <table class="w-full history-table">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">ID Number</th>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Sit-In Purpose</th>
                            <th class="px-6 py-3 font-medium">Laboratory</th>
                            <th class="px-6 py-3 font-medium">Login</th>
                            <th class="px-6 py-3 font-medium">Logout</th>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Row 1 -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">2020-00001</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-2">JD</div>
                                    John Doe
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Programming Assignment</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                    Programming Lab
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">09:30 AM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">11:45 AM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Mar 13, 2025</td>
                            <td class="px-6 py-4">
                                <button class="feedback-btn bg-indigo-600 text-white text-xs px-3 py-1.5 rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                    <i class="fas fa-comment-alt mr-1"></i>
                                    <span>Feedback</span>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 2 -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">2020-00001</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-2">JD</div>
                                    John Doe
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Database Project</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-50 text-green-700">
                                    Database Lab
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">02:15 PM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">04:30 PM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Mar 12, 2025</td>
                            <td class="px-6 py-4">
                                <button class="feedback-btn bg-indigo-600 text-white text-xs px-3 py-1.5 rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                    <i class="fas fa-comment-alt mr-1"></i>
                                    <span>Feedback</span>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 3 -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">2020-00001</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-2">JD</div>
                                    John Doe
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Multimedia Editing</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-purple-50 text-purple-700">
                                    Multimedia Lab
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">10:00 AM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">12:30 PM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Mar 10, 2025</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                    Submitted
                                </span>
                            </td>
                        </tr>
                        
                        <!-- Row 4 -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">2020-00001</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-2">JD</div>
                                    John Doe
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Web Development</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-orange-50 text-orange-700">
                                    Web Lab
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">01:00 PM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">03:15 PM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Mar 8, 2025</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                    Submitted
                                </span>
                            </td>
                        </tr>
                        
                        <!-- Row 5 -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">2020-00001</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-2">JD</div>
                                    John Doe
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Network Assignment</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-red-50 text-red-700">
                                    Network Lab
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">09:00 AM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">11:30 AM</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Mar 5, 2025</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                    Submitted
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <div class="hidden sm:block">
                    <p class="text-sm text-gray-500">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">24</span> results
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <button class="px-3 py-1 border rounded-md bg-blue-50 text-blue-600 font-medium">1</button>
                    <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50">3</button>
                    <span class="px-3 py-1 text-gray-500">...</span>
                    <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50">5</button>
                    
                    <button class="px-3 py-1 border rounded-md text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feedback Modal (Hidden by default) -->
    <div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800">Session Feedback</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5">
                <form>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">How would you rate this session?</p>
                        <div class="flex space-x-2 text-2xl">
                            <button type="button" class="text-gray-300 hover:text-yellow-500 transition-colors duration-200">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="text-gray-300 hover:text-yellow-500 transition-colors duration-200">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="text-gray-300 hover:text-yellow-500 transition-colors duration-200">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="text-gray-300 hover:text-yellow-500 transition-colors duration-200">
                                <i class="fas fa-star"></i>
                            </button>
                            <button type="button" class="text-gray-300 hover:text-yellow-500 transition-colors duration-200">
                                <i class="fas fa-star"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm text-gray-600 mb-2">Comments</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="4" placeholder="Share your experience with this lab session..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm text-gray-600 mb-2">Suggestions for Improvement</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="2" placeholder="Any suggestions to improve the lab facilities or experience?"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button id="cancelFeedback" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    Cancel
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Submit Feedback
                </button>
            </div>
        </div>
    </div>

    <script>
        // Wait for the DOM to be fully loaded before attaching event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality (just UI for now)
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    // UI feedback for search (to be implemented later)
                    console.log('Searching for: ' + e.target.value);
                });
            }
            
            // Feedback modal toggle
            const feedbackButtons = document.querySelectorAll('.feedback-btn');
            const feedbackModal = document.getElementById('feedbackModal');
            const modalContent = document.getElementById('modalContent');
            const closeModal = document.getElementById('closeModal');
            const cancelFeedback = document.getElementById('cancelFeedback');
            
            if (feedbackButtons.length > 0 && feedbackModal && modalContent) {
                feedbackButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        feedbackModal.classList.remove('hidden');
                        setTimeout(() => {
                            modalContent.classList.remove('scale-95', 'opacity-0');
                            modalContent.classList.add('scale-100', 'opacity-100');
                        }, 10);
                    });
                });
                
                const hideModal = () => {
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        feedbackModal.classList.add('hidden');
                    }, 300);
                };
                
                if (closeModal) {
                    closeModal.addEventListener('click', hideModal);
                }
                
                if (cancelFeedback) {
                    cancelFeedback.addEventListener('click', hideModal);
                }
                
                feedbackModal.addEventListener('click', function(e) {
                    if (e.target === feedbackModal) {
                        hideModal();
                    }
                });
            }
            
            // Initialize star rating functionality
            const starButtons = document.querySelectorAll('.fa-star');
            if (starButtons.length > 0) {
                starButtons.forEach((star, index) => {
                    star.parentElement.addEventListener('click', function() {
                        // Reset all stars
                        starButtons.forEach(s => {
                            s.parentElement.classList.remove('text-yellow-500');
                            s.parentElement.classList.add('text-gray-300');
                        });
                        
                        // Fill stars up to the clicked one
                        for (let i = 0; i <= index; i++) {
                            starButtons[i].parentElement.classList.remove('text-gray-300');
                            starButtons[i].parentElement.classList.add('text-yellow-500');
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
  