<?php

include 'connection.php'; // Assuming you have a connection to your database

// Prepare and execute SQL statement to retrieve all data from the table
$sql = "SELECT * FROM HeartBreathData";
$result = $conn->query($sql);

// Check if any rows are returned
if ($result->num_rows > 0) {
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data[0]);
}

// Close connection
$conn->close();

?>