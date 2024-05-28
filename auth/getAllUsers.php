<?php
include '../connection.php';

// Select all users from the database
$sql = "SELECT id, username, email, first_name, last_name, address, city, state, zip_code, country, phone FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array to store user data
    $users = array();

    // Fetch each row and add it to the users array
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    // Return the users array as JSON
    echo json_encode(["status" => "success", "users" => $users]);
} else {
    // No users found
    echo json_encode(["status" => "error", "message" => "No users found"]);
}

$conn->close();
?>
