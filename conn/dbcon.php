<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'sit-in');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>