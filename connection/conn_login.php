<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'conn/dbcon.php';
?>


<style>
.swal2-confirm {
    width: auto !important;
    padding: 0.625em 2em !important;
    margin: 0 !important;
    background-image: none !important;
    box-shadow: none !important;
}

.swal2-actions {
    width: auto !important;
    margin: 1.25em auto 0 !important;
    gap: 0 !important;
}

.swal2-popup {
    padding: 0 0 1.25em 0 !important;
}

.swal2-styled.swal2-confirm::before,
.swal2-styled.swal2-confirm::after {
    display: none !important;
}
</style>


<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Check if the password is hashed
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row;
            $_SESSION['just_logged_in'] = true; // Set session variable
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Login Successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'homepage.php';
                        });
                    });
                  </script>";
        } else {
            // Check for plain text password comparison
            if ($password == $row['password']) {
                $_SESSION['user'] = $row;
                $_SESSION['just_logged_in'] = true; // Set session variable
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Login Successfully',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'homepage.php';
                            });
                        });
                      </script>";
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Oops!',
                                text: 'Invalid email or password',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'login.php';
                            });
                        });
                      </script>";
            }
        }
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Invalid email or password',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                });
              </script>";
    }
}

if (isset($_POST['register'])) {
    $idno = $_POST['idno'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $course = $_POST['course'];
    $level = $_POST['level'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Store plain text password

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Username already exists',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                });
              </script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (idno, lastname, firstname, midname, course, level, address, email, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $idno, $lastname, $firstname, $midname, $course, $level, $address, $email, $password);
        $result = $stmt->execute();

        if ($result) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Registration successful',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Registration failed',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    });
                  </script>";
        }
    }
}
?>