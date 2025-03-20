<?php 
    include 'connection/new_pass.php';
?>
<!-- Add this flash message alert section -->
<?php if (isset($_SESSION['flash_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?php echo $_SESSION['flash_alert_type']; ?>',
                title: '<?php echo $_SESSION['flash_alert_type'] === "success" ? "Success!" : "Error"; ?>',
                text: '<?php echo $_SESSION['flash_message']; ?>',
                confirmButtonColor: '<?php echo $_SESSION['flash_alert_type'] === "success" ? "#3B82F6" : "#EF4444"; ?>'
            });
        });
    </script>
    <?php 
        // Clear flash message after displaying
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_alert_type']);
    ?>
<?php endif; ?>

<!-- Keep your existing message alert -->
<?php if ($message): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?php echo $alertType; ?>',
                title: '<?php echo $alertType === "success" ? "Success!" : "Error"; ?>',
                text: '<?php echo $message; ?>',
                confirmButtonColor: '<?php echo $alertType === "success" ? "#3B82F6" : "#EF4444"; ?>'
            });
        });
    </script>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="icon" type="image/png" href="images/wbccs.png">
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <style>
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6B7280;
        }
        .progress-bar {
            height: 4px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-5 -mt-2">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Set New Password</h2>
                        <p class="text-gray-500 text-xs mt-1">Create a new password for your account</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2">
                        <i class="fas fa-lock text-blue-600 text-lg"></i>
                    </div>
                </div>
                
                <!-- Student Info and Current Password Summary -->
                <div class="mb-5 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-2">
                        <?php if (!empty($user['profileImg'])): ?>
                            <img src="<?php echo $user['profileImg']; ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-500"></i>
                            </div>
                        <?php endif; ?>
                        <div class="ml-3">
                            <p class="font-medium text-sm text-gray-800"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></p>
                            <p class="text-xs text-gray-500"><?php echo $user['idno']; ?></p>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Current Password:</span>
                            <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded"><?php echo $displayPassword; ?></span>
                        </div>
                    </div>
                </div>
                
                <?php if ($message): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: '<?php echo $alertType; ?>',
                                title: '<?php echo $alertType === "success" ? "Success!" : "Error"; ?>',
                                text: '<?php echo $message; ?>',
                                confirmButtonColor: '<?php echo $alertType === "success" ? "#3B82F6" : "#EF4444"; ?>'
                            });
                        });
                    </script>
                <?php endif; ?>
                
                <form action="" method="POST" id="passwordForm" class="space-y-4">
                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="password-container">
                            <input type="password" name="new_password" id="new_password" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Create new password"
                                onkeyup="checkPasswordStrength(this.value)">
                            <span class="password-toggle" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-1">
                                <div id="password-strength-bar" class="progress-bar bg-red-500 rounded-full h-1" style="width: 0%"></div>
                            </div>
                            <p id="password-strength-text" class="mt-1 text-xs text-gray-500">Password strength: Too weak</p>
                        </div>
                    </div>
                    
                    <!-- Confirm New Password -->
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <div class="password-container">
                            <input type="password" name="confirm_password" id="confirm_password" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Confirm your new password"
                                onkeyup="checkPasswordMatch()">
                            <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <p id="password-match" class="mt-1 text-xs text-gray-500">Passwords do not match</p>
                    </div>
                    
                    <!-- Password Guidelines - Simpler version -->
                    <div class="bg-blue-50 p-2 rounded text-xs text-blue-700">
                        <p>Use at least 8 characters with uppercase, number & special character.</p>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="edit.php" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-1.5"></i>
                            Back
                        </a>
                        <button type="submit" id="submit-btn"
                            class="px-4 py-1.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                            Set New Password
                            <i class="fas fa-check ml-1.5"></i>
                        </button>
                    </div>
                    <input type="hidden" id="password_strength" name="password_strength" value="0">
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePassword(id) {
            const passwordInput = document.getElementById(id);
            const passwordToggle = passwordInput.nextElementSibling.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            }
        }
        

        // Check password strength
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            const submitBtn = document.getElementById('submit-btn');
            
            // Default values
            let strength = 0;
            let bgColor = 'bg-red-500';
            let text = 'Too weak';
            
            // Calculate password strength
            if (password.length >= 8) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 25;
            
            // Set text and color based on strength
            if (strength == 100) {
                bgColor = 'bg-green-500';
                text = 'Strong';
            } else if (strength >= 75) {
                bgColor = 'bg-blue-500';
                text = 'Good';
            } else if (strength >= 50) {
                bgColor = 'bg-yellow-500';
                text = 'Medium';
            } else if (strength >= 25) {
                bgColor = 'bg-orange-500';
                text = 'Weak';
            }
            
            // Update UI
            strengthBar.style.width = strength + '%';
            strengthBar.className = `progress-bar ${bgColor} rounded-full h-1`;
            strengthText.innerText = 'Password strength: ' + text;
            
            // Store the strength level as a data attribute for later use
            document.getElementById('new_password').dataset.strength = strength;
            
            // Check passwords match for enabling submit button
            checkPasswordMatch();
        }

        // Now modify the password match function to enable the button even for medium strength
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordMatch = document.getElementById('password-match');
            const submitBtn = document.getElementById('submit-btn');
            
            if (confirmPassword.length === 0) {
                passwordMatch.innerText = "Passwords do not match";
                passwordMatch.className = "mt-1 text-xs text-gray-500";
                submitBtn.disabled = true;
                return;
            }
            
            if (password === confirmPassword) {
                passwordMatch.innerText = "Passwords match!";
                passwordMatch.className = "mt-1 text-xs text-green-500";
                
                submitBtn.disabled = false;
                // Instead of: submitBtn.disabled = !(password.length >= 8);
            } else {
                passwordMatch.innerText = "Passwords do not match";
                passwordMatch.className = "mt-1 text-xs text-red-500";
                submitBtn.disabled = true;
            }
        }

        // Add this return statement after the SweetAlert for weak passwords
        document.addEventListener('DOMContentLoaded', function() {
            // Add form submit handler
            document.getElementById('passwordForm').addEventListener('submit', function(e) {
                // First check if fields are empty
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                // If either field is empty, show error alert and prevent form submission
                if (!newPassword || !confirmPassword) {
                    e.preventDefault();
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Empty Fields',
                        text: 'Please fill in both password fields before submitting',
                        confirmButtonColor: '#EF4444'
                    });
                    return; // This return is correct
                }
                
                // Get password strength
                const strength = parseInt(document.getElementById('new_password').dataset.strength || 0);
                
                // If strength is less than 100 (not Strong), prevent form submission and show appropriate alert
                if (strength < 100) {
                    e.preventDefault();
                    
                    // Get the strength text and appropriate styling based on the score
                    let strengthText, alertIcon, alertTitle, buttonColor;
                    
                    if (strength >= 75) {
                        strengthText = 'good, but not strong enough';
                        alertIcon = 'info';
                        alertTitle = 'Almost There!';
                        buttonColor = '#3B82F6';
                    } else if (strength >= 50) {
                        strengthText = 'medium, and needs improvement';
                        alertIcon = 'warning';
                        alertTitle = 'Password Too Weak';
                        buttonColor = '#F59E0B';
                    } else if (strength >= 25) {
                        strengthText = 'weak, and needs significant improvement';
                        alertIcon = 'warning';
                        alertTitle = 'Password Too Weak';
                        buttonColor = '#F97316';
                    } else {
                        strengthText = 'too weak';
                        alertIcon = 'error';
                        alertTitle = 'Password Too Weak';
                        buttonColor = '#EF4444';
                    }
                    
                    // Show the Sweet Alert with appropriate styling
                    Swal.fire({
                        icon: alertIcon,
                        title: alertTitle,
                        html: `Your password is <b>${strengthText}</b>.<br><br>` +
                            'Please create a stronger password that includes:<br>' +
                            '• At least 8 characters<br>' + 
                            '• Uppercase letters (A-Z)<br>' +
                            '• Numbers (0-9)<br>' +
                            '• Special characters (!@#$%^&*)',
                        confirmButtonColor: buttonColor
                    });
                    return; // ADD THIS RETURN to prevent form submission after showing the alert
                }
            });
        });
    </script>
</body>
</html>