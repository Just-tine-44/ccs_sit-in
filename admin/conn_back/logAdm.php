<?php
session_start();
include __DIR__ . "/../../conn/dbcon.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // User input (plain text)

    // Get stored password from database
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Check if the password is hashed or plain text
        if ($stored_password === $password || password_verify($password, $stored_password)) {
            $_SESSION['admin'] = $username;
            $_SESSION['login_success'] = true; // ✅ Store success flag in session

            // Redirect to admin_home.php
            header("Location: admin_home.php");
            exit(); 
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: 'Invalid username or password!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location = 'admin_login.php';
                        });
                    });
                  </script>";
        }
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: 'Invalid username or password!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'admin_login.php';
                    });
                });
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
