<?php
    include("connection/history_process.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sit-In History</title>
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Sit-In History</h1>
        
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
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-600">Total Sessions</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['total_sessions']; ?></span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-green-600">Total Hours</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $totalHours; ?></span>
                    <span class="ml-2 text-sm text-gray-500">hrs <?php echo $remainingMinutes; ?> min</span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <h3 class="text-lg font-semibold text-yellow-600">Rated Sessions</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['rated_sessions']; ?></span>
                    <span class="ml-2 text-sm text-gray-500">of <?php echo $stats['completed_sessions']; ?> completed</span>
                </div>
            </div>
        </div>
        
        <!-- History Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Session History</h2>
            </div>
            
            <?php if ($historyResult->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratory</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time-In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time-Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $historyResult->fetch_assoc()): ?>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?php echo htmlspecialchars($row['laboratory']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo htmlspecialchars($row['purpose']); ?>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php if ($row['status'] == 'completed'): ?>
                                            <?php if ($row['rating']): ?>
                                                <div class="flex items-center">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= $row['rating'] ? 'text-yellow-500' : 'text-gray-300'; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-400">Not rated</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($row['status'] == 'completed'): ?>
                                            <button 
                                                onclick="openRatingModal(
                                                    <?php echo $row['sit_in_id']; ?>, 
                                                    '<?php echo htmlspecialchars($row['purpose']); ?>', 
                                                    '<?php echo htmlspecialchars($row['laboratory']); ?>',
                                                    <?php echo $row['rating'] ? $row['rating'] : 0; ?>,
                                                    '<?php echo htmlspecialchars($row['feedback'] ?? ''); ?>'
                                                )" 
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                <?php echo $row['rating'] ? 'Edit Rating' : 'Rate Session'; ?>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="py-8 text-center">
                    <div class="inline-flex rounded-full bg-gray-100 p-4">
                        <div class="rounded-full stroke-gray-500 bg-gray-200 p-4">
                            <svg class="w-8 h-8" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2C7.373 2 2 7.373 2 14C2 20.627 7.373 26 14 26C20.627 26 26 20.627 26 14C26 7.373 20.627 2 14 2ZM14 24C8.486 24 4 19.514 4 14C4 8.486 8.486 4 14 4C19.514 4 24 8.486 24 14C24 19.514 19.514 24 14 24Z" fill="#A1A1AA"/>
                                <path d="M14 10C14.5523 10 15 9.55228 15 9C15 8.44772 14.5523 8 14 8C13.4477 8 13 8.44772 13 9C13 9.55228 13.4477 10 14 10Z" fill="#A1A1AA"/>
                                <path d="M13 12C13 11.4477 13.4477 11 14 11C14.5523 11 15 11.4477 15 12V19C15 19.5523 14.5523 20 14 20C13.4477 20 13 19.5523 13 19V12Z" fill="#A1A1AA"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h2 class="mt-4 text-lg font-medium text-gray-900">No history found</h2>
                    <p class="mt-2 text-sm text-gray-500">You haven't had any sit-in sessions yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden animate__animated animate__fadeIn">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Rate Your Experience</h3>
                <button onclick="closeRatingModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="ratingForm" method="POST" action="">
                <div class="p-6">
                    <input type="hidden" id="modal-sit-in-id" name="sit_in_id">
                    <input type="hidden" id="rating" name="rating" value="0">
                    
                    <div class="mb-4">
                        <p class="text-gray-700 mb-1"><strong>Purpose:</strong> <span id="modal-purpose"></span></p>
                        <p class="text-gray-700"><strong>Laboratory:</strong> <span id="modal-lab"></span></p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Your Rating:</label>
                        <div class="flex space-x-2">
                            <span class="cursor-pointer text-gray-300" onclick="setRating(1)"><i class="fas fa-star"></i></span>
                            <span class="cursor-pointer text-gray-300" onclick="setRating(2)"><i class="fas fa-star"></i></span>
                            <span class="cursor-pointer text-gray-300" onclick="setRating(3)"><i class="fas fa-star"></i></span>
                            <span class="cursor-pointer text-gray-300" onclick="setRating(4)"><i class="fas fa-star"></i></span>
                            <span class="cursor-pointer text-gray-300" onclick="setRating(5)"><i class="fas fa-star"></i></span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="feedback" class="block text-gray-700 mb-2">Feedback (Optional):</label>
                        <textarea id="feedback" name="feedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-lg">
                    <button type="button" onclick="closeRatingModal()" class="mr-2 px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" name="submit_rating" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setRating(rating) {
            document.getElementById('rating').value = rating;
            
            const stars = document.querySelectorAll('#ratingForm .fa-star');
            stars.forEach((star, index) => {
                star.parentElement.classList.remove('text-yellow-500');
                star.parentElement.classList.add('text-gray-300');
                
                if (index < rating) {
                    star.parentElement.classList.remove('text-gray-300');
                    star.parentElement.classList.add('text-yellow-500');
                }
            });
        }
        
        function openRatingModal(sitInId, purpose, laboratory, rating, feedback) {
            document.getElementById('modal-sit-in-id').value = sitInId;
            document.getElementById('modal-purpose').textContent = purpose;
            document.getElementById('modal-lab').textContent = laboratory;
            document.getElementById('feedback').value = feedback;
            setRating(rating);
            
            document.getElementById('ratingModal').classList.remove('hidden');
        }
        
        function closeRatingModal() {
            document.getElementById('ratingModal').classList.add('hidden');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside
            document.getElementById('ratingModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRatingModal();
                }
            });
            
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