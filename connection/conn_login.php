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

            // Fetch the session information
            $stmt = $conn->prepare("SELECT session FROM stud_session WHERE id=?");
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();
            $session_result = $stmt->get_result();

            if ($session_result->num_rows > 0) {
                $stud_session = $session_result->fetch_assoc();
                $_SESSION['stud_session'] = $stud_session;
            } else {
                $_SESSION['stud_session'] = ['session' => 'N/A'];
            }

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

                // Fetch the session information
                $stmt = $conn->prepare("SELECT session FROM stud_session WHERE id=?");
                $stmt->bind_param("i", $row['id']);
                $stmt->execute();
                $session_result = $stmt->get_result();

                if ($session_result->num_rows > 0) {
                    $stud_session = $session_result->fetch_assoc();
                    $_SESSION['stud_session'] = $stud_session;
                } else {
                    $_SESSION['stud_session'] = ['session' => 'N/A'];
                }

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

    // Check if the email already exists
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
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (idno, lastname, firstname, midname, course, level, address, email, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $idno, $lastname, $firstname, $midname, $course, $level, $address, $email, $password);
        $result = $stmt->execute();

        if ($result) {
            // Get the last inserted user ID
            $last_id = $conn->insert_id;

            // Insert into stud_session
            $stmt2 = $conn->prepare("INSERT INTO stud_session (id, session) VALUES (?, 30)");
            $stmt2->bind_param("i", $last_id);
            $stmt2->execute();

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