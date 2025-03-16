<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connection/conn_login.php'; 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UC-CCS Login</title>
    <link rel="icon" type="image/png" href="images/uclogo.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box active" id="login-form">
            <form action="login.php" method="post">
                <div class="logo">
                    <img src="images/uclogo.jpg">
                    <img src="images/ccslogo.png"> 
                </div>
                <h2>CCS Sit-in Monitoring System</h2>
                <input type="email" name="email" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="align">
                    <button type="submit" name="login">Login</button>
                    <p>Don't have an account yet? <a href="#" onclick="showForm('register-form')">Register</a></p>
                </div>

                <div style="text-align: right;">
                    <a href="admin/admin_login.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Admin</a>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box" id="register-form">
            <form action="login.php" method="post">
                <h2>Registration</h2>
                <input type="text" name="idno" placeholder="ID Number" oninput="validateIDNO(this)" required>
                <input type="text" name="lastname" placeholder="Lastname" required>
                <input type="text" name="firstname" placeholder="Firstname" required>
                <input type="text" name="midname" placeholder="Middlename">
                <select name="course" required>
                    <option value="" disabled selected>Course</option>
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
                <select name="level" required>
                    <option value="" disabled selected>Year Level</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
                <input type="text" name="address" placeholder="Address" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="aligntwo">
                    <button type="submit" name="register">Register</button> 
                    <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find the admin link
    const adminLink = document.querySelector('a[href="admin/admin_login.php"]');
    
    // Add click event listener
    adminLink.addEventListener('click', function(e) {
        // Prevent default navigation
        e.preventDefault();
        
        // Show authentication prompt
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
            background: '#fff',
            customClass: {
                input: 'swal-input',
                confirmButton: 'swal-confirm-button',
                cancelButton: 'swal-cancel-button'
            },
            preConfirm: async (code) => {
                try {
                    // Send the code to a verification endpoint
                    const response = await fetch('verify_admin_code.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ code: code })
                    });
                    
                    const data = await response.json();
                    
                    if (!data.success) {
                        return Swal.showValidationMessage(
                            `Invalid authentication code`
                        );
                    }
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    );
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Show a small toast-like notification in the top-right corner
                Swal.fire({
                    title: 'Access Granted',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1100,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal-toast-popup'
                    }
                }).then(() => {
                    // Redirect after success message
                    window.location.href = 'admin/admin_login.php';
                });
            }
        });
    });
});
</script>

<style>
/* Toast popup styling */
.swal-toast-popup {
    padding: 0.75rem !important;
    width: 200px !important;
    font-size: 14px !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    border-left: 4px solid #28a745 !important;
}
.swal-toast-popup .swal2-title {
    margin: 0 !important;
    font-size: 16px !important;
}
.swal-toast-popup .swal2-icon {
    margin: 0.25rem auto !important;
    font-size: 20px !important;
    width: 1.75em !important;
    height: 1.75em !important;
}
.swal-toast-popup .swal2-icon .swal2-icon-content {
    font-size: 1.25rem !important;
}

/* Admin authentication form styling */
.swal-input {
    border: 1px solid #d9d9d9 !important;
    border-radius: 4px !important;
    padding: 10px !important;
    box-shadow: none !important;
    transition: border-color 0.3s !important;
}
.swal-input:focus {
    border-color: #3085d6 !important;
    box-shadow: 0 0 0 3px rgba(48, 133, 214, 0.2) !important;
}
.swal-confirm-button {
    background-color: #3085d6 !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    padding: 10px 24px !important;
}
.swal2-cancel.swal-cancel-button {
    background-color: #dc3545 !important; /* Red background */
    color: white !important;
    border: 1px solid #c82333 !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    padding: 10px 24px !important;
    box-shadow: none !important;
    width: auto !important;
    transition: background-color 0.15s ease-in-out !important;
}

.swal2-cancel.swal-cancel-button:hover {
    background-color: #bd2130 !important; /* Darker red on hover */
    border-color: #bd2130 !important;
}
</style>
</body>
</html>