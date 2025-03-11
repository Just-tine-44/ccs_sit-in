<?php
include '../conn/dbcon.php';

// Handle student checkout/timeout
if (isset($_POST['checkout'])) {
    $sit_in_id = $_POST['sit_in_id'];
    
    // Get the sit-in record to update
    $query = "SELECT * FROM curr_sit_in WHERE sit_in_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sit_in_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $sit_in = $result->fetch_assoc();
        $user_id = $sit_in['user_id'];
        
        // Start transaction to ensure both operations succeed or fail together
        $conn->begin_transaction();
        
        try {
            // 1. Update sit-in record to mark as completed
            $updateQuery = "UPDATE curr_sit_in SET check_out_time = NOW(), status = 'completed' WHERE sit_in_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $sit_in_id);
            $updateStmt->execute();
            
            // 2. Decrement the session count
            $decrementQuery = "UPDATE stud_session SET session = session - 1 WHERE id = ? AND session > 0";
            $decrementStmt = $conn->prepare($decrementQuery);
            $decrementStmt->bind_param("i", $user_id);
            $decrementStmt->execute();
            
            // If everything succeeds, commit the transaction
            $conn->commit();
            
            $_SESSION['message'] = "Student checked out and session count updated successfully";
            $_SESSION['msg_type'] = "success";
        } catch (Exception $e) {
            // If any error occurs, rollback the transaction
            $conn->rollback();
            
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['msg_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Sit-in record not found";
        $_SESSION['msg_type'] = "error";
    }
    
    // Redirect to prevent form resubmission
    header("Location: sit_in.php");
    exit();
}

// Get all active sit-in sessions with student info
$query = "SELECT s.sit_in_id, s.user_id, s.laboratory, s.purpose, s.check_in_time, 
                 u.idno, u.firstname, u.midname, u.lastname, u.course, u.level, 
                 ss.session as remaining_sessions
          FROM curr_sit_in s
          JOIN users u ON s.user_id = u.id
          JOIN stud_session ss ON u.id = ss.id
          WHERE s.status = 'active'
          ORDER BY s.check_in_time DESC";
$result = $conn->query($query);

// Get active sit-in count for each lab
$labCounts = [];
$countQuery = "SELECT laboratory, COUNT(*) as count FROM curr_sit_in WHERE status = 'active' GROUP BY laboratory";
$countResult = $conn->query($countQuery);

if ($countResult->num_rows > 0) {
    while ($row = $countResult->fetch_assoc()) {
        $labCounts[$row['laboratory']] = $row['count'];
    }
}

// Get total active sit-ins
$totalQuery = "SELECT COUNT(*) as total FROM curr_sit_in WHERE status = 'active'";
$totalResult = $conn->query($totalQuery);
$totalActive = $totalResult->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Sit-In Sessions</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar_admin.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Total active sit-ins -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-600">Total Active</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $totalActive; ?></span>
                    <span class="ml-2 text-sm text-gray-500">students</span>
                </div>
            </div>
            
            <!-- Per Laboratory Stats -->
            <?php 
            $labList = ["CCS Lab 1", "CCS Lab 2", "CCS Lab 3", "CCS Lab 4"];
            $colors = ["green", "purple", "yellow", "red"];
            
            for ($i = 0; $i < count($labList); $i++) {
                $lab = $labList[$i];
                $color = $colors[$i];
                $count = isset($labCounts[$lab]) ? $labCounts[$lab] : 0;
                ?>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-<?php echo $color; ?>-500">
                    <h3 class="text-lg font-semibold text-<?php echo $color; ?>-600"><?php echo $lab; ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-3xl font-bold text-gray-800"><?php echo $count; ?></span>
                        <span class="ml-2 text-sm text-gray-500">students</span>
                    </div>
                </div>
            <?php } ?>
        </div>
        
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
            
            <!-- Table of Active Sit-In Sessions -->
            <div class="overflow-x-auto">
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
                                    Check-In Time
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
                                        <form method="POST" action="" class="inline" onsubmit="return confirm('Are you sure you want to check out this student?');">
                                            <input type="hidden" name="sit_in_id" value="<?php echo $row['sit_in_id']; ?>">
                                            <button type="submit" name="checkout" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded-lg transition-colors">
                                                <i class="fas fa-sign-out-alt mr-1"></i> Check Out
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-10">
                        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-blue-100 rounded-full">
                            <i class="fas fa-users text-blue-500 text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Active Sit-In Sessions</h3>
                        <p class="mt-1 text-sm text-gray-500">There are currently no students using the laboratories.</p>
                        <div class="mt-6">
                            <a href="admin_search.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Search Students
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
        // Confirm before checking out a student
        function confirmCheckout(sitInId) {
            Swal.fire({
                title: 'Check Out Student?',
                text: "This will mark the student as checked out and end their sit-in session.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, check out'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('checkout-form-' + sitInId).submit();
                }
            });
            return false;
        }
    </script>
</body>
</html>