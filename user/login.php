<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../connection/conn_login.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UC-CCS Login</title>
    <link rel="icon" type="image/png" href="../images/ccswb.png">
    <link href="../css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <!-- Back to home link -->
    <a href="../index.php" class="absolute top-6 left-6 text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
    </a>

    <!-- Main container -->
    <div class="w-full max-w-md">
        <!-- Form container with card styling -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Login Form -->
            <div id="login-form" class="active-form">
                <div class="p-8">
                    <!-- Logo Header -->
                    <div class="flex justify-center gap-4 mb-6">
                        <img src="../images/uclogo.jpg" alt="UC Logo" class="h-16 w-16 object-contain">
                        <img src="../images/ccslogo.png" alt="CCS Logo" class="h-16 w-16 object-contain">
                    </div>
                    
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">CCS Sit-in Monitoring System</h2>
                    
                    <!-- Login Form -->
                    <form action="login.php" method="post" class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="name@example.com" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="••••••••" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                    <i class="fas fa-eye-slash text-gray-400 toggle-password"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                        
                        <button type="submit" name="login" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium rounded-lg text-sm px-5 py-3 text-center hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            Login
                        </button>
                        
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-600">
                                Don't have an account yet? 
                                <a href="#" onclick="toggleForm('register-form')" class="text-blue-600 hover:text-blue-800 font-medium">Register</a>
                            </p>
                        </div>
                    </form>
                    
                    <!-- Admin button -->
                    <div class="mt-6 text-center">
                        <a href="../admin/admin_login.php" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <i class="fas fa-user-shield mr-2"></i>
                            Admin Panel
                        </a>
                    </div>
                </div>
                
                <!-- Decorative wave -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2"></div>
            </div>

            <!-- Registration Form -->
            <div id="register-form" class="hidden-form">
                <div class="p-6">
                    <!-- Title -->
                    <h2 class="text-xl font-bold text-center text-gray-800 mb-4">Create an Account</h2>
                    
                    <!-- Registration Form -->
                    <form action="login.php" method="post" class="space-y-3">
                        <!-- 3-Column Layout for ID, Course, Year -->
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label for="idno" class="block text-xs font-medium text-gray-700 mb-1">ID</label>
                                <input type="text" id="idno" name="idno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="ID number" oninput="validateIDNO(this)" required>
                            </div>
                            <div>
                                <label for="course" class="block text-xs font-medium text-gray-700 mb-1">Course</label>
                                <select id="course" name="course" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" required>
                                    <option value="" disabled selected>Select</option>
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
                            <div>
                                <label for="level" class="block text-xs font-medium text-gray-700 mb-1">Year</label>
                                <select id="level" name="level" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Name fields - 3 columns -->
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label for="lastname" class="block text-xs font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="lastname" name="lastname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Last name" required>
                            </div>
                            <div>
                                <label for="firstname" class="block text-xs font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="firstname" name="firstname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="First name" required>
                            </div>
                            <div>
                                <label for="midname" class="block text-xs font-medium text-gray-700 mb-1">M.I.</label>
                                <input type="text" id="midname" name="midname" maxlength="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Middle initial">
                            </div>
                        </div>
                        
                        <!-- Address and Email - 2 columns -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="address" class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" id="address" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Enter your address" required>
                            </div>
                            <div>
                                <label for="reg-email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="reg-email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="name@example.com" required>
                            </div>
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label for="reg-password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="reg-password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Create a password" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                    <i class="fas fa-eye-slash text-gray-400 toggle-password"></i>
                                </div>
                            </div>
                            <div class="mt-1 flex items-center gap-2">
                                <div class="h-1 w-24 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-1 bg-red-500 rounded-full" style="width: 0%" id="password-strength"></div>
                                </div>
                                <p class="text-xs text-gray-500">Use 8+ chars with letters, numbers & symbols</p>
                            </div>
                        </div>
                        
                        <button type="submit" name="register" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium rounded-lg text-sm px-4 py-2 text-center hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            Create Account
                        </button>
                        
                        <div class="text-center mt-2">
                            <p class="text-xs text-gray-600">
                                Already have an account? 
                                <a href="#" onclick="toggleForm('login-form')" class="text-blue-600 hover:text-blue-800 font-medium">Login</a>
                            </p>
                        </div>
                    </form>
                </div>
                
                <!-- Decorative wave -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2"></div>
            </div>
        </div>
        
        <!-- Footer text -->
        <p class="text-center text-gray-500 text-xs mt-4">
            AI-Powered ICT: Building the Future with Responsibility
        </p>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle between login and registration forms
        function toggleForm(formId) {
            if (formId === 'login-form') {
                document.getElementById('login-form').classList.remove('hidden-form');
                document.getElementById('login-form').classList.add('active-form');
                document.getElementById('register-form').classList.add('hidden-form');
                document.getElementById('register-form').classList.remove('active-form');
                // Change page title for login
                document.title = 'UC-CCS Login';
            } else {
                document.getElementById('register-form').classList.remove('hidden-form');
                document.getElementById('register-form').classList.add('active-form');
                document.getElementById('login-form').classList.add('hidden-form');
                document.getElementById('login-form').classList.remove('active-form');
                // Change page title for registration
                document.title = 'UC-CCS Registration';
            }
        }
        
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const passwordField = this.parentElement.previousElementSibling.tagName === 'INPUT' 
                        ? this.parentElement.previousElementSibling 
                        : this.parentElement.querySelector('input[type="password"]');
                    
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordField.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });
            });
            
            // Simple password strength meter
            const passwordField = document.getElementById('reg-password');
            const strengthBar = document.getElementById('password-strength');
            
            if (passwordField && strengthBar) {
                passwordField.addEventListener('input', function() {
                    const val = this.value;
                    let strength = 0;
                    
                    if (val.length >= 8) strength += 25;
                    if (val.match(/[a-z]+/)) strength += 25;
                    if (val.match(/[A-Z]+/)) strength += 25;
                    if (val.match(/[0-9]+/)) strength += 15;
                    if (val.match(/[^a-zA-Z0-9]+/)) strength += 10;
                    
                    strengthBar.style.width = strength + '%';
                    
                    // Change color based on strength
                    if (strength < 30) {
                        strengthBar.className = 'h-1 bg-red-500 rounded-full';
                    } else if (strength < 60) {
                        strengthBar.className = 'h-1 bg-yellow-500 rounded-full';
                    } else {
                        strengthBar.className = 'h-1 bg-green-500 rounded-full';
                    }
                });
            }
            
            // Admin authentication
            const adminLink = document.querySelector('a[href="../admin/admin_login.php"]');
            
            if (adminLink) {
                adminLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Admin Authentication',
                        input: 'password',
                        inputPlaceholder: 'Enter authentication code',
                        inputAttributes: {
                            autocapitalize: 'off',
                            autocorrect: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Verify',
                        showLoaderOnConfirm: true,
                        confirmButtonColor: '#4F46E5',
                        background: '#fff',
                        customClass: {
                            container: 'admin-auth-modal',
                            popup: 'rounded-xl shadow-2xl',
                            input: 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md'
                        },
                        preConfirm: async (code) => {
                            try {
                                // Show loader for at least 800ms for better user experience
                                const [response] = await Promise.all([
                                    fetch('verify_admin_code.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({ code: code })
                                    }),
                                    new Promise(resolve => setTimeout(resolve, 800)) // Minimum delay of 800ms
                                ]);
                                
                                const data = await response.json();
                                
                                if (!data.success) {
                                    return Swal.showValidationMessage(
                                        `Invalid authentication code`
                                    );
                                }
                                return data;
                            } catch (error) {
                                return Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                );
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Access Granted',
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-lg shadow-md border-l-4 border-green-500'
                                }
                            }).then(() => {
                                window.location.href = '../admin/admin_login.php';
                            });
                        }
                    });
                });
            }
            
            // Check URL for form parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('form') === 'register') {
                toggleForm('register-form');
            }
            
            // ID Number validation function
            window.validateIDNO = function(input) {
                // Allow only numbers
                input.value = input.value.replace(/[^0-9]/g, '');
                // Limit to 9 digits
                if (input.value.length > 9) {
                    input.value = input.value.slice(0, 9);
                }
            };
        });
    </script>

    <style>
        /* Form transition animations */
        .active-form {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        .hidden-form {
            display: none;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Admin authentication toast styling */
        .rounded-lg.shadow-md.border-l-4.border-green-500 {
            padding: 0.75rem !important;
            width: 250px !important;
        }
        
        /* Compact registration form styles */
        #register-form input, 
        #register-form select {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        #register-form label {
            margin-bottom: 0.15rem;
        }
        
        #register-form .space-y-3 > * {
            margin-top: 0.75rem;
            margin-bottom: 0;
        }
        
        #register-form .space-y-3 > *:first-child {
            margin-top: 0;
        }
        
        #register-form input::placeholder {
            font-size: 0.75rem;
        }
    </style>
</body>
</html>