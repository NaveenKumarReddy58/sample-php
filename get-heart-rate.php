<?php

include 'connection.php'; // Assuming you have a connection to your database

// Initialize an empty array to store the retrieved data
$heart_rate_data_array = array();

// SQL query to select data from the heart_rate_data table
$sql = "SELECT * FROM heart_rate_data";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch each row of the result set and store it in the array
    while ($row = $result->fetch_assoc()) {
        $heart_rate_data_array[] = array(
            'id' => $row['id'],
            'date' => $row['date'],
            'resting_heart_rate' => $row['resting_heart_rate'],
            'performance_heart_rate' => $row['performance_heart_rate'],
            'created_at' => $row['created_at']
        );
    }
} else {
    echo "No data found in the heart_rate_data table";
}

// Close the database connection
$conn->close();

// Print or process the retrieved data array as needed
echo json_encode($heart_rate_data_array);

?>
