<?php
// filepath: c:\xampp\htdocs\login\admin\conn_back\reset_pc.php

// Include database connection
require(__DIR__ . '/../../conn/dbcon.php');

// Get all 'reserved' PCs
$query = "SELECT lab_room, pc_number, last_updated 
          FROM lab_computers 
          WHERE status = 'reserved'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lab_room = $row['lab_room'];
        $pc_number = $row['pc_number'];
        $last_updated = $row['last_updated'] ?? null;
        
        // If last_updated is more than 30 minutes ago or is null, reset to available
        if ($last_updated === null || (time() - strtotime($last_updated) > 1800)) {
            // Check if this PC has an upcoming approved reservation
            $check_reservation = "SELECT reservation_id FROM reservations 
                                WHERE lab_room = ? AND pc_number = ? AND status = 'approved'
                                AND ((reservation_date = CURDATE() AND time_in > CURTIME()) OR reservation_date > CURDATE())";
            $check_stmt = $conn->prepare($check_reservation);
            $check_stmt->bind_param("ss", $lab_room, $pc_number);
            $check_stmt->execute();
            $reservation_result = $check_stmt->get_result();
            
            // Only reset if there's no upcoming reservation for this PC
            if ($reservation_result->num_rows == 0) {
                $update = "UPDATE lab_computers 
                         SET status = 'available', last_updated = NOW() 
                         WHERE lab_room = '$lab_room' AND pc_number = '$pc_number'";
                // Rest of your code...
            } else {
                error_log("Not resetting PC $pc_number in Room $lab_room because it has an upcoming approved reservation");
            }
        }
    }
} else {
    echo "No reserved PCs found to reset.";
}

echo "Done!";
?>