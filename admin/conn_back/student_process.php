<?php
    session_start();
    include(__DIR__ . '/../../conn/dbcon.php');

    // Check if admin is logged in
    if (!isset($_SESSION['admin'])) {
        header("Location: ../admin_login.php");
        exit();
    }

    // Add Student
    if(isset($_POST['add_student'])) {
        $idno = $_POST['idno'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $midname = $_POST['midname'] ?? null;
        $course = $_POST['course'];
        $level = $_POST['level'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        
        // Check if student ID already exists
        $checkQuery = "SELECT * FROM users WHERE idno = ? OR email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $idno, $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if($result->num_rows > 0) {
            $_SESSION['message'] = "Student ID or email already exists";
            $_SESSION['msg_type'] = "error";
        } else {
            // Start transaction to ensure both operations complete or fail together
            $conn->begin_transaction();
            
            try {
                // Insert new student
                $insertQuery = "INSERT INTO users (idno, lastname, firstname, midname, course, level, address, email, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("sssssssss", $idno, $lastname, $firstname, $midname, $course, $level, $address, $email, $password);
                $stmt->execute();
                
                // Get the inserted user's ID
                $userId = $conn->insert_id;
                
                // Insert default session for the new student
                $sessionInsertQuery = "INSERT INTO stud_session (id, session) VALUES (?, 30)";
                $sessionStmt = $conn->prepare($sessionInsertQuery);
                $sessionStmt->bind_param("i", $userId);
                $sessionStmt->execute();
                
                // If we got here, both operations succeeded, so commit the transaction
                $conn->commit();
                
                $_SESSION['message'] = "Student added successfully";
                $_SESSION['msg_type'] = "success";
            } catch (Exception $e) {
                // An error occurred, roll back the transaction
                $conn->rollback();
                
                $_SESSION['message'] = "Error: Could not add student: " . $e->getMessage();
                $_SESSION['msg_type'] = "error";
            }
        }
        
        header("Location: ../admin_student_list.php");
        exit();
    }

    // Update Student
    if(isset($_POST['update_student'])) {
        $student_id = $_POST['student_id'];
        $idno = $_POST['idno'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $midname = $_POST['midname'] ?? null;
        $course = $_POST['course'];
        $level = $_POST['level'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        
        // Check if student ID already exists for other students
        $checkQuery = "SELECT * FROM users WHERE (idno = ? OR email = ?) AND id != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ssi", $idno, $email, $student_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if($result->num_rows > 0) {
            $_SESSION['message'] = "Student ID or email already exists for another student";
            $_SESSION['msg_type'] = "error";
        } else {
            // Check if password was provided for update
            if(!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET idno = ?, lastname = ?, firstname = ?, midname = ?, 
                            course = ?, level = ?, address = ?, email = ?, password = ? WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("sssssssssi", $idno, $lastname, $firstname, $midname, $course, $level, 
                                $address, $email, $password, $student_id);
            } else {
                // Update without changing password
                $updateQuery = "UPDATE users SET idno = ?, lastname = ?, firstname = ?, midname = ?, 
                            course = ?, level = ?, address = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ssssssssi", $idno, $lastname, $firstname, $midname, $course, $level, 
                                $address, $email, $student_id);
            }
            
            if($stmt->execute()) {
                $_SESSION['message'] = "Student updated successfully";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Error: Could not update student";
                $_SESSION['msg_type'] = "error";
            }
        }
        
        header("Location: ../admin_student_list.php");
        exit();
    }

    // Redirect if accessed directly
    header("Location: ../admin_student_list.php");
    exit();
?>