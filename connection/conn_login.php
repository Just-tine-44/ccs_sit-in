<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'conn/dbcon.php';  

// Login functionality
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to find the user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // The hash and plain-text being combined
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    
        // Check if the stored password is hashed
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row;
            echo "<script>
                alert('Login Successfully');
                setTimeout(function() {
                    window.location.href = 'homepage.php';
                }, 100); // .1-second delay before redirection
                </script>";
                exit();
        } else {
            // Check for plain text password comparison for testing
            if ($password == $row['password']) {
                $_SESSION['user'] = $row;
                echo "<script>
                alert('Login Successfully');
                setTimeout(function() {
                    window.location.href = 'homepage.php';
                }, 100); // .1-second delay before redirection
                </script>";
                exit();
            } else {
                echo "<script>alert('Invalid email or password');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }   
}

// Registration functionality
if (isset($_POST['register'])) {
    $idno = $_POST['idno'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $course = $_POST['course'];
    $level = $_POST['level'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Save the password directly without hashing
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before saving it

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists');</script>";
    } else {
        // Register the new user
        $stmt = $conn->prepare("INSERT INTO users (idno, lastname, firstname, midname, course, level, address, email, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $idno, $lastname, $firstname, $midname, $course, $level, $address, $email, $password);
        $result = $stmt->execute();

        if ($result) {
            echo "<script>
                    alert('Registration successful');
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 100); // .1-second delay before redirection
                  </script>";
            exit();
        } else {
            echo "<script>alert('Registration failed');</script>";
        }
    }
}
?>




<!-- // if ($result->num_rows == 1) { // With hashed password
    //     $row = $result->fetch_assoc();
    //     if (password_verify($password, $row['password'])) {
    //         $_SESSION['email'] = $email;
    //         header('Location: homepage.php'); // Redirect to homepage
    //         exit(); // Exit after redirection
    //     } else {
    //         echo "<script>alert('Invalid email or password');</script>";
    //     }
    // } else {
    //     echo "<script>alert('Invalid email or password');</script>";
    // }

    // if ($result->num_rows == 1) { // Without hashed password
    //     $row = $result->fetch_assoc();
    //     // Compare plain text passwords directly
    //     if ($password == $row['password']) {
    //         $_SESSION['email'] = $email;
    //         header('Location: homepage.php'); // Redirect to homepage
    //         exit(); // Exit after redirection
    //     } else {
    //         echo "<script>alert('Invalid email or password');</script>";
    //     }
    // } else {
    //     echo "<script>alert('Invalid email or password');</script>";
    // } 

    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before saving it -->