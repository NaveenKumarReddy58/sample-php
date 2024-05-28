<?php
include 'connection.php';

// Process data sent from Angular frontend
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sqlTruncate = "TRUNCATE TABLE HeartBreathData";
if ($conn->query($sqlTruncate) === TRUE) {
     // Retrieve heart rate and breath rate from the FormData object
     $heartRate = $_POST['heart_rate'];
     $breathRate = $_POST['breath_rate'];
     $dateString = $_POST['last_submitted_date'];
     $lastSubmittedDate = date('Y-m-d H:i:s', strtotime($dateString));
 
     // Prepare and execute SQL statement to insert data into the database
     $sql = "INSERT INTO HeartBreathData (heartRate, breathRate,lastSubmittedDate) VALUES (?, ?, ?)";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("iis", $heartRate, $breathRate,$lastSubmittedDate);
 
     $stmt->execute();
     // Close statement and connection
     $stmt->close();
     $conn->close();
}
   
} else {
    echo "Invalid request method";
}
?>