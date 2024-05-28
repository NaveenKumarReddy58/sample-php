<?php

include 'connection.php';
include 'fitbit_credentials.php';

// Specify the number of days for which you want to fetch heart rate data
$days_to_fetch = 1; // Change this as needed

// Fitbit API endpoint for heart rate data
$api_url = 'https://api.fitbit.com/1/user/-/activities/heart/date/today/-'. $days_to_fetch .'d.json';

// Set up cURL options
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_error($ch)) {
    echo 'Error:' . curl_error($ch);
}

// Close cURL resource
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);
// Prepare and execute SQL statements to insert data
foreach ($data['categories'] as $category) {
    $category_id = $category['id'];
    $category_name = $category['name'];

    foreach ($category['activities'] as $activity) {
        $activity_id = $activity['id'];
        $activity_name = $activity['name'];
        $mets = isset($activity['mets']) ? $activity['mets'] : null;
        $access_level = $activity['accessLevel'];

        // Prepare SQL statement
        $sql = "INSERT INTO activity_data (category_id, activity_id, activity_name, mets, access_level) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters and execute the statement
        $stmt->bind_param("iisds", $category_id, $activity_id, $activity_name, $mets, $access_level);
        $stmt->execute();
    }
}

// Close prepared statement and database connection
$stmt->close();
$conn->close();

?>
