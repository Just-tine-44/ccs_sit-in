<?php include("./conn_back/sit-in_process.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("icon.php"); ?>
    <title>Current Sit-In Sessions</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar_admin.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Main Content Area -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i> Current Sit-In Sessions
                </h1>
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
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <div class="flex -mb-px">
                    <button id="direct-tab" class="tab-button px-6 py-4 font-medium text-sm text-blue-600 border-b-2 border-blue-500 bg-blue-50 whitespace-nowrap">
                        <i class="fas fa-user-clock mr-2"></i> Direct Sit-Ins <span class="ml-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full"><?php echo $result->num_rows; ?></span>
                    </button>
                    <button id="reservation-tab" class="tab-button px-6 py-4 font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                        <i class="fas fa-calendar-check mr-2"></i> From Reservations <span class="ml-1 bg-gray-500 text-white text-xs px-1.5 py-0.5 rounded-full"><?php echo $reservationResult->num_rows; ?></span>
                    </button>
                </div>
            </div>
            
            <!-- Direct Sit-In Table -->
            <div id="direct-content" class="tab-content overflow-x-auto">
                <?php if ($result->num_rows > 0): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Course & Year
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Laboratory
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                   Time-In
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Purpose
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Remaining Sessions
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo htmlspecialchars($row['idno']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . ($row['midname'] ? substr($row['midname'], 0, 1) . '.' : '')); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php echo htmlspecialchars($row['course'] . ' - ' . $row['level']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?php echo htmlspecialchars($row['laboratory']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y g:i A', strtotime($row['check_in_time'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-xs overflow-hidden text-ellipsis">
                                            <?php echo htmlspecialchars($row['purpose']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $row['remaining_sessions'] <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                            <?php echo $row['remaining_sessions']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" onclick="handleCheckout(<?php echo $row['sit_in_id']; ?>, 'direct')" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-sign-out-alt mr-1"></i> Timeout
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-10">
                        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-blue-100 rounded-full">
                            <i class="fas fa-user-clock text-blue-500 text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Active Direct Sit-In Sessions</h3>
                        <p class="mt-1 text-sm text-gray-500">There are currently no students using the laboratories via direct sit-in.</p>
                        <div class="mt-6">
                            <a href="admin_search.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Search Students
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Reservation Sit-In Table -->
            <div id="reservation-content" class="tab-content overflow-x-auto hidden">
                <?php if ($reservationResult->num_rows > 0): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Course & Year
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Laboratory
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    PC Number
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reservation Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Start Time
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    End Time
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Purpose
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $reservationResult->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo htmlspecialchars($row['idno']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . ($row['midname'] ? substr($row['midname'], 0, 1) . '.' : '')); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php echo htmlspecialchars($row['course'] . ' - ' . $row['level']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            <?php echo htmlspecialchars($row['lab_room']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            PC <?php echo htmlspecialchars($row['pc_number']); ?>
                                        </span>
                                    </td>
                                    <!-- New status column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                        $current_time = date('H:i:s');
                                        $start_time = $row['time_in'];
                                        $is_active = strtotime($current_time) >= strtotime($start_time);
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $is_active ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo $is_active ? 'Active' : 'Upcoming'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($row['reservation_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('g:i A', strtotime($row['time_in'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                        if ($row['status'] === 'completed' && !empty($row['time_out'])) {
                                            echo date('g:i A', strtotime($row['time_out']));
                                        } else {
                                            echo '<span class="text-gray-400">Pending</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-xs overflow-hidden text-ellipsis">
                                            <?php echo htmlspecialchars($row['purpose']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php 
                                        $current_time = date('H:i:s');
                                        $start_time = $row['time_in'];
                                        $is_active = strtotime($current_time) >= strtotime($start_time);
                                        
                                        if ($is_active): ?>
                                            <button type="button" onclick="handleCheckout(<?php echo $row['reservation_id']; ?>, 'reservation')" 
                                                    class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded-lg transition-colors">
                                                <i class="fas fa-sign-out-alt mr-1"></i> End Session
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Not started yet</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-10">
                        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-indigo-100 rounded-full">
                            <i class="fas fa-calendar-check text-indigo-500 text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Active Reserved Sessions</h3>
                        <p class="mt-1 text-sm text-gray-500">There are currently no active reservation-based sessions.</p>
                        <div class="mt-6">
                            <a href="admin_reservation.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-calendar-alt mr-2"></i> View Reservations
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- History Button -->
        <div class="mt-6 text-center">
            <a href="records.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700">
                <i class="fas fa-history mr-2"></i> View Sit-In History
            </a>
        </div>
    </div>

<script>
// ==============================================
// SEPARATE FUNCTIONS FOR DIRECT SIT-INS AND RESERVATIONS
// ==============================================

// Main handler function that routes to the appropriate specific function
function handleCheckout(id, type) {
    if (type === 'direct') {
        handleDirectSitInTimeout(id);
    } else if (type === 'reservation') {
        handleReservationEndSession(id);
    }
}

// Function specifically for handling direct sit-in timeouts
function handleDirectSitInTimeout(id) {
    let formData = new FormData();
    formData.append('sit_in_id', id);
    formData.append('checkout', '1');
    
    Swal.fire({
        title: 'Time Out Student?',
        text: "This will mark the student as timed out and end their sit-in session.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, time out'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we update the records.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send AJAX request
            fetch('conn_back/ajax_sit_in.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Use simple success message like original functionality
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Student checked out and session count updated successfully',
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        // Reload the page to refresh data
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Something went wrong'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network or server error occurred'
                });
            });
        }
    });
}

// Function specifically for handling reservation end sessions
function handleReservationEndSession(id) {
    let formData = new FormData();
    formData.append('reservation_id', id);
    formData.append('checkout_reservation', '1');
    
    Swal.fire({
        title: 'End Reservation Session?',
        text: "This will mark the reservation session as completed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, end session'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we update the records.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send AJAX request
            fetch('conn_back/ajax_sit_in.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // For reservations, show exact end time
                    let studentInfo = data.student_name ? `<span class="text-sm text-gray-600">Student: ${data.student_name}</span><br>` : '';
                    let endTimeDisplay = `<div class="mt-3 p-3 bg-gray-100 rounded text-center">
                        ${studentInfo}
                        <span class="text-sm text-gray-600">Session ended at:</span><br>
                        <span class="text-lg font-semibold text-gray-800">${data.end_time}</span>
                    </div>`;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Reservation Session Ended',
                        html: endTimeDisplay,
                        showConfirmButton: true,
                        confirmButtonText: 'Done',
                        timer: 5000
                    }).then(() => {
                        // Reload the page to refresh data
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Something went wrong'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network or server error occurred'
                });
            });
        }
    });
}

// Tab switching logic
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Reset all tabs
            tabs.forEach(t => {
                t.classList.remove('text-blue-600', 'border-b-2', 'border-blue-500', 'bg-blue-50');
                t.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Reset all tab contents
            contents.forEach(c => c.classList.add('hidden'));
            
            // Activate selected tab
            this.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            this.classList.add('text-blue-600', 'border-b-2', 'border-blue-500', 'bg-blue-50');
            
            // Show selected content
            const contentId = this.id.replace('-tab', '-content');
            document.getElementById(contentId).classList.remove('hidden');
        });
    });
});
</script>
</body>
</html>