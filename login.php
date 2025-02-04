<?php 
    include 'connection/conn_login.php'; // Include the connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form</title>
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
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box" id="register-form">
            <form action="login.php" method="post">
                <h2>Registration</h2>
                <input type="text" name="idno" placeholder="ID Number" required>
                <input type="text" name="lastname" placeholder="Lastname" required>
                <input type="text" name="firstname" placeholder="Firstname" required>
                <input type="text" name="midname" placeholder="Middlename" required>
                <select name="course" required>
                    <option value="" disabled selected>Course</option>
                    <option value="bsit">BSIT</option>
                    <option value="bscs">BSCS</option>
                    <option value="act">ACT</option>
                </select>
                <select name="level" required>
                    <option value="" disabled selected>Year Level</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
                <input type="email" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="aligntwo">
                    <button type="submit" name="register">Register</button> 
                    <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
                </div>
            </form>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>
