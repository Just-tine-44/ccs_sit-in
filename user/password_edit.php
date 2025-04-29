<?php 
    include '../connection/new_pass.php';
?>
<!-- Flash message alert section -->
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
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_alert_type']);
    ?>
<?php endif; ?>

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
    <link rel="icon" type="image/png" href="../images/wbccs.png">
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.03'%3E%3Cpath d='M0 38.59l2.83-2.83 1.41 1.41L1.41 40H0v-1.41zM0 1.4l2.83 2.83 1.41-1.41L1.41 0H0v1.41zM38.59 40l-2.83-2.83 1.41-1.41L40 38.59V40h-1.41zM40 1.41l-2.83 2.83-1.41-1.41L38.59 0H40v1.41zM20 18.6l2.83-2.83 1.41 1.41L21.41 20l2.83 2.83-1.41 1.41L20 21.41l-2.83 2.83-1.41-1.41L18.59 20l-2.83-2.83 1.41-1.41L20 18.59z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .security-pattern {
            background-color: #f0f5ff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.08'%3E%3Cpath d='M50 0C22.386 0 0 22.386 0 50s22.386 50 50 50 50-22.386 50-50S77.614 0 50 0zm0 11.596C70.967 11.596 88.404 29.033 88.404 50c0 20.967-17.437 38.404-38.404 38.404C29.033 88.404 11.596 70.967 11.596 50c0-20.967 17.437-38.404 38.404-38.404zm0 10.202C34.761 21.798 21.798 34.761 21.798 50c0 15.239 12.963 28.202 28.202 28.202 15.239 0 28.202-12.963 28.202-28.202 0-15.239-12.963-28.202-28.202-28.202z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
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
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #4B5563;
        }
        
        .progress-bar {
            height: 4px;
            transition: width 0.4s ease, background-color 0.4s ease;
        }
        
        .custom-shadow {
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 8px 10px -5px rgba(59, 130, 246, 0.04);
        }
        
        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Page Header -->
        <div class="text-center mb-6">
            <div class="inline-block p-3 rounded-full bg-blue-100 mb-3">
                <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Secure Your Account</h1>
            <p class="text-gray-500 text-sm">Create a strong password to protect your information</p>
        </div>
        
        <!-- Main Card -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-lg custom-shadow">
            <!-- Card Header with Security Pattern -->
            <div class="security-pattern p-6 border-b border-blue-100 relative overflow-hidden">
                <div class="relative z-10 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Set New Password</h2>
                    <div class="bg-white bg-opacity-90 p-2 rounded-full shadow-sm">
                        <i class="fas fa-lock text-blue-600"></i>
                    </div>
                </div>
                
                <!-- Decorative Security Icons -->
                <div class="absolute top-1/2 left-1/4 transform -translate-y-1/2 opacity-10">
                    <i class="fas fa-fingerprint text-blue-800 text-4xl"></i>
                </div>
                <div class="absolute bottom-0 right-6 transform translate-y-1/3 opacity-10">
                    <i class="fas fa-key text-blue-800 text-5xl"></i>
                </div>
            </div>
            
            <!-- Student Info -->
            <div class="p-5 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center">
                    <?php if (!empty($user['profileImg'])): ?>
                        <img src="<?php
                            // Check if path already has "../" prefix
                            echo strpos($user['profileImg'], '../') === 0 
                                ? $user['profileImg'] 
                                : '../' . $user['profileImg'];
                        ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white border-2 border-white shadow-sm">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                    <div class="ml-3">
                        <p class="font-medium text-gray-800"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></p>
                        <div class="flex items-center">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full"><?php echo $user['idno']; ?></span>
                            <div class="flex items-center ml-2 text-xs text-gray-500">
                                <span class="mr-1">Current Pass:</span>
                                <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono text-gray-600"><?php echo $displayPassword; ?></code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Password Form -->
            <form action="" method="POST" id="passwordForm" class="p-6">
                <!-- New Password -->
                <div class="mb-5">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="password-container">
                        <input type="password" name="new_password" id="new_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors text-gray-800"
                            placeholder="Create new password"
                            onkeyup="checkPasswordStrength(this.value)">
                        <span class="password-toggle" onclick="togglePassword('new_password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full">
                            <div id="password-strength-bar" class="progress-bar bg-red-500 rounded-full" style="width: 0%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <p id="password-strength-text" class="text-xs text-gray-500">Password strength: Too weak</p>
                            <p id="strength-percentage" class="text-xs font-medium text-gray-400">0%</p>
                        </div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="mt-3 grid grid-cols-2 gap-1">
                        <div class="flex items-center text-xs">
                            <i id="req-length" class="fas fa-circle text-gray-300 text-[8px] mr-1.5"></i>
                            <span class="text-gray-600">At least 8 characters</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i id="req-upper" class="fas fa-circle text-gray-300 text-[8px] mr-1.5"></i>
                            <span class="text-gray-600">Uppercase letter (A-Z)</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i id="req-number" class="fas fa-circle text-gray-300 text-[8px] mr-1.5"></i>
                            <span class="text-gray-600">Number (0-9)</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i id="req-special" class="fas fa-circle text-gray-300 text-[8px] mr-1.5"></i>
                            <span class="text-gray-600">Special character (!@#$)</span>
                        </div>
                    </div>
                </div>
                
                <!-- Confirm New Password -->
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" name="confirm_password" id="confirm_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors text-gray-800"
                            placeholder="Confirm your new password"
                            onkeyup="checkPasswordMatch()">
                        <span class="password-toggle" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <p id="password-match" class="mt-1.5 text-xs text-gray-500">Passwords do not match</p>
                </div>
                
                <!-- Buttons -->
                <div class="flex items-center justify-between mt-8">
                    <a href="edit.php" class="flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Profile
                    </a>
                    <button type="submit" id="submit-btn"
                        class="flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium">
                        Save Password
                        <i class="fas fa-shield-alt ml-2"></i>
                    </button>
                </div>
                <input type="hidden" id="password_strength" name="password_strength" value="0">
            </form>
        </div>
        
        <div class="text-center mt-4 text-xs text-gray-500">
            <p>Need help? <a href="#" class="text-blue-600 hover:underline">Contact support</a></p>
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
            const strengthPercentage = document.getElementById('strength-percentage');
            const submitBtn = document.getElementById('submit-btn');
            
            // Default values
            let strength = 0;
            let bgColor = 'bg-red-500';
            let text = 'Too weak';
            
            // Update requirement indicators
            document.getElementById('req-length').className = password.length >= 8 ? 'fas fa-check-circle text-green-500 mr-1.5 text-[10px]' : 'fas fa-circle text-gray-300 text-[8px] mr-1.5';
            document.getElementById('req-upper').className = password.match(/[A-Z]/) ? 'fas fa-check-circle text-green-500 mr-1.5 text-[10px]' : 'fas fa-circle text-gray-300 text-[8px] mr-1.5';
            document.getElementById('req-number').className = password.match(/[0-9]/) ? 'fas fa-check-circle text-green-500 mr-1.5 text-[10px]' : 'fas fa-circle text-gray-300 text-[8px] mr-1.5';
            document.getElementById('req-special').className = password.match(/[^a-zA-Z0-9]/) ? 'fas fa-check-circle text-green-500 mr-1.5 text-[10px]' : 'fas fa-circle text-gray-300 text-[8px] mr-1.5';
            
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
            strengthBar.className = `progress-bar ${bgColor} rounded-full`;
            strengthText.innerText = 'Password strength: ' + text;
            strengthPercentage.innerText = strength + '%';
            
            // Store the strength level as a data attribute for later use
            document.getElementById('new_password').dataset.strength = strength;
            
            // Check passwords match for enabling submit button
            checkPasswordMatch();
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordMatch = document.getElementById('password-match');
            
            // Update the password match text
            if (confirmPassword.length === 0) {
                passwordMatch.innerText = "Please confirm your password";
                passwordMatch.className = "mt-1.5 text-xs text-gray-500";
            } else if (password === confirmPassword) {
                passwordMatch.innerHTML = "<i class='fas fa-check-circle mr-1'></i> Passwords match!";
                passwordMatch.className = "mt-1.5 text-xs text-green-500 flex items-center";
            } else {
                passwordMatch.innerHTML = "<i class='fas fa-times-circle mr-1'></i> Passwords do not match";
                passwordMatch.className = "mt-1.5 text-xs text-red-500 flex items-center";
            }
            
            // Always keep the submit button enabled for validation to work server-side
            document.getElementById('password_strength').value = document.getElementById('new_password').dataset.strength || 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('passwordForm');
            
            // Clone the form to remove existing event listeners
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
            
            // Add fresh event listener
            newForm.addEventListener('submit', function(e) {
                // Always prevent default first to ensure our validation runs
                e.preventDefault();
                
                // Get fresh values
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                // If either field is empty, show error alert
                if (!newPassword || !confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Empty Fields',
                        text: 'Please fill in both password fields before submitting',
                        confirmButtonColor: '#EF4444'
                    });
                    return false;
                }
                
                // Check if passwords match
                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Don\'t Match',
                        text: 'Your passwords do not match. Please try again.',
                        confirmButtonColor: '#EF4444'
                    });
                    return false;
                }
                
                // Get password strength
                const strength = parseInt(document.getElementById('new_password').dataset.strength || 0);
                
                // If strength is less than 100 (not Strong), show appropriate alert
                if (strength < 100) {
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
                    return false;
                }
                
                // If we get here, all validations passed - submit the form
                this.submit();
            });
            
            // Reattach event handlers
            const newPasswordField = document.getElementById('new_password');
            if (newPasswordField) {
                newPasswordField.onkeyup = function() {
                    checkPasswordStrength(this.value);
                };
            }
            
            const confirmPasswordField = document.getElementById('confirm_password');
            if (confirmPasswordField) {
                confirmPasswordField.onkeyup = function() {
                    checkPasswordMatch();
                };
            }
        });
    </script>
</body>
</html>