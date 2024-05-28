<?php
include '../connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    // Validate and sanitize the inputs
    $username = $conn->real_escape_string($data['username']);
    $password = $conn->real_escape_string($data['password']);

    // Check if the user exists and the password matches
    $check_user = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check_user->num_rows > 0) {
        $user = $check_user->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, login successful
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "email" => $user['email'],
                    "first_name" => $user['first_name'],
                    "last_name" => $user['last_name'],
                    "phone" => $user['phone']
                ]
            ]);
        } else {
            // Password is incorrect
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        // User does not exist
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    $conn->close();
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
