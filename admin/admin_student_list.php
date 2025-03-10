<?php 
    include("navbar_admin.php");
    session_start();
    
    // Check if admin is logged in
    if (!isset($_SESSION['admin'])) {
        header("Location: admin_login.php");
        exit();
    }

    require_once('../conn/dbcon.php');

    // Handle student deletion
    if(isset($_POST['delete_student'])) {
        $student_id = $_POST['student_id'];
        $deleteQuery = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $student_id);
        
        if($stmt->execute()) {
            $_SESSION['message'] = "Student successfully deleted";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not delete student";
            $_SESSION['msg_type'] = "danger";
        }
        header("Location: admin_student_list.php");
        exit();
    }

    // Handle session reset for a specific student
    if(isset($_POST['reset_sessions'])) {
        $student_id = $_POST['student_id'];
        $updateQuery = "UPDATE stud_session SET session = 30 WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $student_id);
        
        if($stmt->execute()) {
            $_SESSION['message'] = "Student sessions reset successfully";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not reset sessions";
            $_SESSION['msg_type'] = "danger";
        }
        header("Location: admin_student_list.php");
        exit();
    }

    // Reset all students' sessions
    if(isset($_POST['reset_all_sessions'])) {
        $updateAllQuery = "UPDATE stud_session SET session = 30";
        if($conn->query($updateAllQuery)) {
            $_SESSION['message'] = "All sessions reset successfully";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not reset all sessions";
            $_SESSION['msg_type'] = "danger";
        }
        header("Location: admin_student_list.php");
        exit();
    }

    // Pagination settings
    $results_per_page = 10;
    if(isset($_GET['entries'])) {
        $results_per_page = intval($_GET['entries']);
    }
    
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($page - 1) * $results_per_page;

    // Search functionality
    $search = '';
    $searchCondition = '';
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        $searchCondition = "WHERE idno LIKE ? OR CONCAT(firstname, ' ', lastname) LIKE ? OR course LIKE ?";
    }

    // Count total records for pagination
    $countQuery = "SELECT COUNT(*) as total FROM users " . $searchCondition;
    $countStmt = $conn->prepare($countQuery);
    
    if(!empty($searchCondition)) {
        $searchParam = "%{$search}%";
        $countStmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    }
    
    $countStmt->execute();
    $totalResult = $countStmt->get_result()->fetch_assoc();
    $total_records = $totalResult['total'];
    $total_pages = ceil($total_records / $results_per_page);

    // Get users with pagination and search
    $query = "SELECT u.*, s.session 
            FROM users u 
            LEFT JOIN stud_session s ON u.id = s.id " . 
            $searchCondition . 
            " ORDER BY u.id DESC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
        
    if(!empty($searchCondition)) {
        $searchParam = "%{$search}%";
        $stmt->bind_param("sssis", $searchParam, $searchParam, $searchParam, $start_from, $results_per_page);
    } else {
        $stmt->bind_param("ii", $start_from, $results_per_page);
    }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $students = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Student List</title>
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <!-- Alert Messages -->
        <?php if(isset($_SESSION['message'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: '<?= $_SESSION['msg_type'] ?>',
                        title: '<?= $_SESSION['message'] ?>',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            </script>
            <?php 
                unset($_SESSION['message']);
                unset($_SESSION['msg_type']);
            ?>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-700"><i class="fas fa-users mr-2"></i>Students Information</h2>
            <div class="space-x-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition" 
                        onclick="openAddModal()">
                    <i class="fas fa-user-plus mr-1"></i> Add Student
                </button>
                <form method="POST" action="admin_student_list.php" class="inline" id="resetAllForm">
                    <input type="hidden" name="reset_all_sessions" value="1">
                    <button type="button" onclick="confirmResetAll()" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-700 transition">
                        <i class="fas fa-sync-alt mr-1"></i> Reset All Sessions
                    </button>
                </form>
            </div>
        </div>

        <!-- Search & Entries -->
        <div class="flex justify-between mb-4">
            <div>
                <form action="admin_student_list.php" method="GET" id="entriesForm">
                    <label class="text-gray-600">Show 
                        <select name="entries" class="border rounded px-2 py-1 ml-1" onchange="document.getElementById('entriesForm').submit()">
                            <option value="10" <?= $results_per_page == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= $results_per_page == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= $results_per_page == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $results_per_page == 100 ? 'selected' : '' ?>>100</option>
                        </select> entries
                    </label>
                    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                    <?php endif; ?>
                    <?php if(isset($_GET['page'])): ?>
                        <input type="hidden" name="page" value="<?= intval($_GET['page']) ?>">
                    <?php endif; ?>
                </form>
            </div>
            <div>
                <form action="admin_student_list.php" method="GET">
                    <div class="relative">
                        <input type="text" name="search" id="searchInput" placeholder="Search students..." class="border rounded px-3 py-2 pl-10 pr-10" value="<?= htmlspecialchars($search) ?>">
                        <div class="absolute left-3 top-2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <?php if(!empty($search)): ?>
                            <div class="absolute right-3 top-2 text-gray-400 cursor-pointer" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_GET['entries'])): ?>
                            <input type="hidden" name="entries" value="<?= intval($_GET['entries']) ?>">
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-2 px-4 border-b">ID Number</th>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Year Level</th>
                        <th class="py-2 px-4 border-b">Course</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Sessions</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($students)): ?>
                        <tr>
                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">No students found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($students as $student): ?>
                            <tr class="border-b hover:bg-gray-100 transition">
                                <td class="py-2 px-4"><?= htmlspecialchars($student['idno']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($student['level']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($student['course']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($student['email']) ?></td>
                                <td class="py-2 px-4">
                                    <?php 
                                        $sessionValue = isset($student['session']) ? $student['session'] : 30; 
                                    ?>
                                    <span class="<?= ($sessionValue <= 5) ? 'text-red-600 font-bold' : (($sessionValue <= 15) ? 'text-yellow-600' : 'text-green-600') ?>">
                                        <?= htmlspecialchars($sessionValue) ?>/30
                                    </span>
                                </td>
                                <td class="py-2 px-4 space-x-1">
                                    <!-- Change this in the table row -->
                                    <button class="px-2 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition" 
                                            onclick="openEditModal(<?= $student['id'] ?>, '<?= htmlspecialchars(addslashes($student['idno'])) ?>', '<?= htmlspecialchars(addslashes($student['firstname'])) ?>', '<?= htmlspecialchars(addslashes($student['lastname'])) ?>', '<?= htmlspecialchars(addslashes($student['midname'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($student['level'])) ?>', '<?= htmlspecialchars(addslashes($student['course'])) ?>', '<?= htmlspecialchars(addslashes($student['email'])) ?>', '<?= htmlspecialchars(addslashes($student['address'])) ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="admin_student_list.php" class="inline" id="resetForm<?= $student['id'] ?>">
                                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                        <input type="hidden" name="reset_sessions" value="1">
                                        <button type="button" 
                                                onclick="confirmReset(<?= $student['id'] ?>, <?= $sessionValue ?>)" 
                                                class="px-2 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="admin_student_list.php" class="inline" id="deleteForm<?= $student['id'] ?>">
                                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                        <input type="hidden" name="delete_student" value="1">
                                        <button type="button" onclick="confirmDelete(<?= $student['id'] ?>)" class="px-2 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 0): ?>
            <div class="flex justify-between items-center mt-4">
                <p class="text-gray-600">
                    Showing <?= $start_from + 1 ?> to <?= min($start_from + $results_per_page, $total_records) ?> of <?= $total_records ?> entries
                </p>
                <div class="flex space-x-1">
                    <?php if($page > 1): ?>
                        <a href="?page=<?= $page-1 ?><?= isset($_GET['entries']) ? '&entries='.$_GET['entries'] : '' ?><?= isset($_GET['search']) ? '&search='.$_GET['search'] : '' ?>" 
                           class="px-3 py-1 border rounded-lg text-gray-600 hover:bg-gray-200">
                           <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <button class="px-3 py-1 border rounded-lg text-gray-400" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    <?php endif; ?>
                    
                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $start_page + 4);
                    
                    for($i = $start_page; $i <= $end_page; $i++): ?>
                        <a href="?page=<?= $i ?><?= isset($_GET['entries']) ? '&entries='.$_GET['entries'] : '' ?><?= isset($_GET['search']) ? '&search='.$_GET['search'] : '' ?>" 
                           class="px-3 py-1 border rounded-lg <?= $i == $page ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-200' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if($page < $total_pages): ?>
                        <a href="?page=<?= $page+1 ?><?= isset($_GET['entries']) ? '&entries='.$_GET['entries'] : '' ?><?= isset($_GET['search']) ? '&search='.$_GET['search'] : '' ?>" 
                           class="px-3 py-1 border rounded-lg text-gray-600 hover:bg-gray-200">
                           <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button class="px-3 py-1 border rounded-lg text-gray-400" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add Student Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-md mx-auto mt-2 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Add New Student</h2>
                <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="./conn_back/student_process.php" method="POST">
                <div class="mb-4">
                    <label for="id_number" class="block text-gray-700 font-medium mb-1">ID Number</label>
                    <input type="text" id="id_number" name="id_number" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="firstname" class="block text-gray-700 font-medium mb-1">First Name</label>
                        <input type="text" id="firstname" name="firstname" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="lastname" class="block text-gray-700 font-medium mb-1">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="midname" class="block text-gray-700 font-medium mb-1">Middle Name (Optional)</label>
                    <input type="text" id="midname" name="midname" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label for="level" class="block text-gray-700 font-medium mb-1">Year Level</label>
                        <select id="level" name="level" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled>Select Year Level</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div>
                        <label for="course" class="block text-gray-700 font-medium mb-1">Course</label>
                        <select id="course" name="course" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled>Select Course</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                            <option value="ACT">ACT</option>
                            <option value="BSCE">BSCE</option>
                            <option value="BSME">BSME</option>
                            <option value="BSEE">BSEE</option>
                            <option value="BSIE">BSIE</option>
                            <option value="BSCompE">BSCompE</option>
                            <option value="BSA">BSA</option>
                            <option value="BSBA">BSBA</option>
                            <option value="BSOA">BSOA</option>
                            <option value="BEEd">BEEd</option>
                            <option value="BSEd">BSEd</option> 
                            <option value="AB PolSci">AB PolSci</option> 
                            <option value="BSCrim">BSCrim</option> 
                            <option value="BSHRM">BSHRM</option> 
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="address" class="block text-gray-700 font-medium mb-1">Address</label>
                    <input type="text" id="address" name="address" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <input type="hidden" name="session" value="30">
                <div class="flex justify-end">
                    <button type="button" onclick="closeAddModal()" class="mr-2 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" name="add_student" class="px-4 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-auto mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Edit Student Information</h2>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="./conn_back/student_process.php" method="POST">
                <input type="hidden" id="edit_id" name="student_id">
                <div class="mb-4">
                    <label for="edit_id_number" class="block text-gray-700 font-medium mb-1">ID Number</label>
                    <input type="text" id="edit_id_number" name="idno" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_firstname" class="block text-gray-700 font-medium mb-1">First Name</label>
                        <input type="text" id="edit_firstname" name="firstname" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="edit_lastname" class="block text-gray-700 font-medium mb-1">Last Name</label>
                        <input type="text" id="edit_lastname" name="lastname" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_midname" class="block text-gray-700 font-medium mb-1">Middle Name (Optional)</label>
                    <input type="text" id="edit_midname" name="midname" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_level" class="block text-gray-700 font-medium mb-1">Year Level</label>
                        <select id="edit_level" name="level" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_course" class="block text-gray-700 font-medium mb-1">Course</label>
                        <select id="edit_course" name="course" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                            <option value="ACT">ACT</option>
                            <option value="BSCE">BSCE</option>
                            <option value="BSME">BSME</option>
                            <option value="BSEE">BSEE</option>
                            <option value="BSIE">BSIE</option>
                            <option value="BSCompE">BSCompE</option>
                            <option value="BSA">BSA</option>
                            <option value="BSBA">BSBA</option>
                            <option value="BSOA">BSOA</option>
                            <option value="BEEd">BEEd</option>
                            <option value="BSEd">BSEd</option> 
                            <option value="AB PolSci">AB PolSci</option> 
                            <option value="BSCrim">BSCrim</option> 
                            <option value="BSHRM">BSHRM</option> 
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_address" class="block text-gray-700 font-medium mb-1">Address</label>
                    <input type="text" id="edit_address" name="address" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit_email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="mr-2 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" name="update_student" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Student</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openEditModal(id, idno, firstname, lastname, midname, level, course, email, address) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_id_number').value = idno;
            document.getElementById('edit_firstname').value = firstname;
            document.getElementById('edit_lastname').value = lastname;
            document.getElementById('edit_midname').value = midname || '';
            
            // Set the selected option in the level dropdown
            const levelSelect = document.getElementById('edit_level');
            for (let i = 0; i < levelSelect.options.length; i++) {
                if (levelSelect.options[i].value === level) {
                    levelSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Set the selected option in the course dropdown
            const courseSelect = document.getElementById('edit_course');
            for (let i = 0; i < courseSelect.options.length; i++) {
                if (courseSelect.options[i].value === course) {
                    courseSelect.selectedIndex = i;
                    break;
                }
            }
            
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_address').value = address;

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Confirmation functions
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
        
        function confirmReset(id, sessionValue) {
            // Check if session is already at maximum (30)
            if (sessionValue >= 30) {
                Swal.fire({
                    title: 'Session Already Full',
                    text: "The session is still full (30/30). There's no need to reset it.",
                    icon: 'info',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            // If not full, show the reset confirmation
            Swal.fire({
                title: 'Reset Sessions?',
                text: "This will reset the student's remaining sessions to 30",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetForm' + id).submit();
                }
            });
        }

        function confirmResetAll() {
            // Check if any sessions need resetting
            let allSessionsFull = true;
            
            // Get all session values from the table
            const sessionElements = document.querySelectorAll('td:nth-child(6) span');
            sessionElements.forEach(element => {
                const sessionText = element.innerText;
                const sessionValue = parseInt(sessionText.split('/')[0]);
                if (sessionValue < 30) {
                    allSessionsFull = false;
                }
            });
            
            if (allSessionsFull) {
                Swal.fire({
                    title: 'All Sessions Already Full',
                    text: "All student sessions are already at maximum (30/30). There's no need to reset them.",
                    icon: 'info',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            // If not all sessions are full, show the reset confirmation
            Swal.fire({
                title: 'Reset All Sessions?',
                text: "This will reset all students' remaining sessions to 30",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetAllForm').submit();
                }
            });
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            window.location.href = 'admin_student_list.php<?= isset($_GET['entries']) ? '?entries='.intval($_GET['entries']) : '' ?>';
        }
    </script>

</body>
</html>