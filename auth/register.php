<?php
include '../connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    // Validate and sanitize the inputs
    $username = $conn->real_escape_string($data['username']);
    $email = $conn->real_escape_string($data['email']);
    $password = password_hash($conn->real_escape_string($data['password']), PASSWORD_BCRYPT);
    $first_name = isset($data['first_name']) ? $conn->real_escape_string($data['first_name']) : null;
    $last_name = isset($data['last_name']) ? $conn->real_escape_string($data['last_name']) : null;
    $address = isset($data['address']) ? $conn->real_escape_string($data['address']) : null;
    $city = isset($data['city']) ? $conn->real_escape_string($data['city']) : null;
    $state = isset($data['state']) ? $conn->real_escape_string($data['state']) : null;
    $zip_code = isset($data['zip_code']) ? $conn->real_escape_string($data['zip_code']) : null;
    $country = isset($data['country']) ? $conn->real_escape_string($data['country']) : null;
    $phone = isset($data['phone']) ? $conn->real_escape_string($data['phone']) : null;

    // Check if the username or email already exists
    $check_user = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($check_user->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username or email already exists."]);
    } else {
        // Insert the user data into the database
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, address, city, state, zip_code, country, phone)
                VALUES ('$username', '$email', '$password', '$first_name', '$last_name', '$address', '$city', '$state', '$zip_code', '$country', '$phone')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Registration successful."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $sql . " - " . $conn->error]);
        }
    }

    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
