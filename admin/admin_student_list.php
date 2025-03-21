<?php include("./conn_back/studentlist_process.php"); ?>
<?php include("navbar_admin.php");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Student List</title>
    <?php include("icon.php"); ?>
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

    <!-- Add Student Modal - HCI Optimized -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50"
        role="dialog" 
        aria-labelledby="addStudentTitle" 
        aria-describedby="addStudentDescription">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-auto my-8 overflow-hidden">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h2 id="addStudentTitle" class="text-lg font-semibold text-gray-800">Add New Student</h2>
                <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded" aria-label="Close modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p id="addStudentDescription" class="sr-only">Form to add a new student to the system</p>
            <form id="addStudentForm" action="./conn_back/student_process.php" method="POST" novalidate>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5">
                    <!-- Personal Information Column -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-user mr-2"></i> Personal Information
                        </h3>
                        
                        <div class="mb-3">
                            <label for="idno" class="block text-gray-700 text-sm font-medium mb-1">
                                ID Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="idno" name="idno" required 
                                pattern="[0-9]+" title="Please enter a valid numeric ID"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                aria-required="true">
                            <p class="text-xs text-gray-500 mt-1">Enter student's numeric ID number</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label for="firstname" class="block text-gray-700 text-sm font-medium mb-1">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="firstname" name="firstname" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    aria-required="true">
                            </div>
                            
                            <div>
                                <label for="lastname" class="block text-gray-700 text-sm font-medium mb-1">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lastname" name="lastname" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    aria-required="true">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="midname" class="block text-gray-700 text-sm font-medium mb-1">
                                Middle Name <span class="text-gray-400">(Optional)</span>
                            </label>
                            <input type="text" id="midname" name="midname" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="block text-gray-700 text-sm font-medium mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                aria-required="true"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                title="Please enter a valid email address">
                            <p class="text-xs text-gray-500 mt-1">Must be a valid email address</p>
                        </div>
                    </div>
                    
                    <!-- Academic Information Column -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i> Academic & Additional Info
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label for="level" class="block text-gray-700 text-sm font-medium mb-1">
                                    Year Level <span class="text-red-500">*</span>
                                </label>
                                <select id="level" name="level" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                        aria-required="true">
                                    <option value="" disabled selected>Select Level</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="course" class="block text-gray-700 text-sm font-medium mb-1">
                                    Course <span class="text-red-500">*</span>
                                </label>
                                <select id="course" name="course" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                        aria-required="true">
                                    <option value="" disabled selected>Select Course</option>
                                    <optgroup label="College of Computer Studies">
                                        <option value="BSIT">BSIT - BS Information Technology</option>
                                        <option value="BSCS">BSCS - BS Computer Science</option>
                                        <option value="ACT">ACT - Associate in Computer Technology</option>
                                    </optgroup>
                                    <optgroup label="College of Engineering">
                                        <option value="BSCE">BSCE - BS Civil Engineering</option>
                                        <option value="BSME">BSME - BS Mechanical Engineering</option>
                                        <option value="BSEE">BSEE - BS Electrical Engineering</option>
                                        <option value="BSIE">BSIE - BS Industrial Engineering</option>
                                        <option value="BSCompE">BSCompE - BS Computer Engineering</option>
                                    </optgroup>
                                    <optgroup label="College of Business">
                                        <option value="BSA">BSA - BS Accountancy</option>
                                        <option value="BSBA">BSBA - BS Business Administration</option>
                                        <option value="BSOA">BSOA - BS Office Administration</option>
                                        <option value="BSHRM">BSHRM - BS Hotel & Restaurant Management</option>
                                    </optgroup>
                                    <optgroup label="College of Arts & Sciences">
                                        <option value="BEEd">BEEd - BS Elementary Education</option>
                                        <option value="BSEd">BSEd - BS Secondary Education</option>
                                        <option value="AB PolSci">AB PolSci - Bachelor of Arts in Political Science</option>
                                        <option value="BSCrim">BSCrim - BS Criminology</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="block text-gray-700 text-sm font-medium mb-1">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="address" name="address" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                aria-required="true">
                        </div>
                        
                        <div class="mb-3 relative">
                            <label for="password" class="block text-gray-700 text-sm font-medium mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required minlength="6"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 pr-10"
                                    aria-required="true">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 flex items-center" tabindex="-1" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye text-gray-500"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Password must be at least 6 characters</p>
                            <div class="password-strength-meter mt-2 hidden">
                                <div class="h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div id="passwordStrength" class="h-1 bg-red-500 transition-all duration-300 ease-in-out" style="width: 0%"></div>
                                </div>
                                <p id="passwordStrengthText" class="text-xs mt-1 text-gray-500">Password strength: <span>Weak</span></p>
                            </div>
                            <div class="text-xs mt-2 bg-yellow-50 border border-yellow-200 p-2 rounded-md flex items-center">
                                <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                                <span class="text-yellow-700">The default password for all students should be <code class="bg-gray-100 px-1 py-0.5 rounded font-mono">CCS@123</code></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="session" value="30">
                
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-medium text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i> Fields marked with <span class="text-red-500">*</span> are required
                        </span>
                    </div>
                    <div class="flex items-center">
                        <button type="button" onclick="closeAddModal()" class="px-4 py-1.5 mr-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button type="submit" name="add_student" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-user-plus mr-1"></i> Add Student
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            
            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    
                    // Toggle eye icon
                    const eyeIcon = this.querySelector('i');
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }
            
            // Password strength meter
            const passwordStrengthBar = document.getElementById('passwordStrength');
            const passwordStrengthText = document.getElementById('passwordStrengthText').querySelector('span');
            const passwordStrengthMeter = document.querySelector('.password-strength-meter');
            
            if (passwordField && passwordStrengthBar) {
                passwordField.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    
                    if (password.length > 0) {
                        passwordStrengthMeter.classList.remove('hidden');
                        
                        // Calculate password strength
                        if (password.length >= 8) strength += 25;
                        if (password.match(/[a-z]+/)) strength += 25;
                        if (password.match(/[A-Z]+/)) strength += 25;
                        if (password.match(/[0-9]+/)) strength += 15;
                        if (password.match(/[^a-zA-Z0-9]+/)) strength += 10;
                        
                        // Update strength meter
                        passwordStrengthBar.style.width = strength + '%';
                        
                        // Update text and color
                        if (strength < 30) {
                            passwordStrengthBar.className = 'h-1 bg-red-500 transition-all duration-300 ease-in-out';
                            passwordStrengthText.textContent = 'Weak';
                            passwordStrengthText.className = 'text-red-500';
                        } else if (strength < 60) {
                            passwordStrengthBar.className = 'h-1 bg-yellow-500 transition-all duration-300 ease-in-out';
                            passwordStrengthText.textContent = 'Moderate';
                            passwordStrengthText.className = 'text-yellow-600';
                        } else {
                            passwordStrengthBar.className = 'h-1 bg-green-500 transition-all duration-300 ease-in-out';
                            passwordStrengthText.textContent = 'Strong';
                            passwordStrengthText.className = 'text-green-600';
                        }
                    } else {
                        passwordStrengthMeter.classList.add('hidden');
                    }
                });
            }
            
            // Form validation
            const addStudentForm = document.getElementById('addStudentForm');
            
            if (addStudentForm) {
                addStudentForm.addEventListener('submit', function(event) {
                    // Remove previous error messages
                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
                    
                    let hasErrors = false;
                    
                    // Check ID Number - should be numeric
                    const idno = document.getElementById('idno');
                    if (idno && !/^\d+$/.test(idno.value)) {
                        showError(idno, 'ID Number must contain only digits');
                        hasErrors = true;
                    }
                    
                    // Check email format
                    const email = document.getElementById('email');
                    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                        showError(email, 'Please enter a valid email address');
                        hasErrors = true;
                    }
                    
                    // Check password length
                    const password = document.getElementById('password');
                    if (password && password.value.length < 6) {
                        showError(password, 'Password must be at least 6 characters');
                        hasErrors = true;
                    }
                    
                    // Validate select fields are chosen
                    const level = document.getElementById('level');
                    if (level && level.selectedIndex === 0) {
                        showError(level, 'Please select a year level');
                        hasErrors = true;
                    }
                    
                    const course = document.getElementById('course');
                    if (course && course.selectedIndex === 0) {
                        showError(course, 'Please select a course');
                        hasErrors = true;
                    }
                    
                    if (hasErrors) {
                        event.preventDefault();
                        
                        // Find first error and focus it
                        const firstError = document.querySelector('.border-red-500');
                        if (firstError) {
                            firstError.focus();
                        }
                    }
                });
            }
            
            function showError(element, message) {
                // Add red border to the element
                element.classList.add('border-red-500');
                
                // Create error message
                const errorDiv = document.createElement('p');
                errorDiv.className = 'error-message text-red-500 text-xs mt-1';
                errorDiv.innerText = message;
                
                // Insert after the element
                element.parentNode.insertBefore(errorDiv, element.nextSibling);
            }
        });
        
        // Keyboard navigation and focus trap for modal
        function openAddModal() {
            const modal = document.getElementById('addModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Focus first form element after modal is shown
            setTimeout(() => {
                const firstInput = modal.querySelector('input, select, button:not([aria-label="Close modal"])');
                if (firstInput) {
                    firstInput.focus();
                }
                
                // Set up focus trap
                trapFocus(modal);
            }, 50);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
            
            // Clear form fields (optional)
            document.getElementById('addStudentForm').reset();
            
            // Remove any validation errors
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
            
            // Hide password strength meter
            document.querySelector('.password-strength-meter').classList.add('hidden');
        }

        function trapFocus(element) {
            // Get all focusable elements
            const focusableElements = element.querySelectorAll(
                'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length === 0) return;
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            // Handle keyboard navigation
            element.addEventListener('keydown', function(e) {
                // Close modal on Escape key
                if (e.key === 'Escape') {
                    closeAddModal();
                    return;
                }
                
                // Trap Tab navigation within modal
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        // If shift + tab and on first element, move to last
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        // If tab and on last element, move to first
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }
    </script>

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