<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include 'connection/conn_login.php'; // Include the connection file

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare the query
        $query = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: homepage.php");
                exit();
            } else {
                echo "<script>
                    alert('Invalid email or password');
                    window.location.href = 'login.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Invalid email or password');
                window.location.href = 'login.php';
            </script>";
        }
    }
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
                <input type="text" name="idno" placeholder="ID Number" oninput="validateIDNO(this)" required>
                <input type="text" name="lastname" placeholder="Lastname" required>
                <input type="text" name="firstname" placeholder="Firstname" required>
                <input type="text" name="midname" placeholder="Middlename" required>
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
</body>
</html>