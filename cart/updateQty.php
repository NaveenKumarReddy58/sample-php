<?php
include '../connection.php'; // Include your database connection file

// Function to update quantity of item in the cart
function updateCartItemQuantity($userId, $productId, $quantity) {
    global $conn;

    // Validate user ID, product ID, and quantity
    if (!is_numeric($userId) || !is_numeric($productId) || !is_numeric($quantity) || $quantity <= 0) {
        return ["status" => "error", "message" => "Invalid user ID, product ID, or quantity"];
    }

    // Prepare SQL statement to update quantity of item in the cart
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $userId, $productId);

    // Execute the statement
    if ($stmt->execute()) {
        return ["status" => "success", "message" => "Quantity updated in cart"];
    } else {
        return ["status" => "error", "message" => "Error updating quantity in cart: " . $conn->error];
    }

    // Close the statement
    $stmt->close();
}

// Get raw JSON data sent from the client
$json_data = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json_data, true);

// Check if "user_id", "product_id", and "quantity" are provided in the JSON data
if (!isset($data['user_id']) || !isset($data['product_id']) || !isset($data['quantity'])) {
    echo json_encode(["status" => "error", "message" => "User ID, product ID, or quantity not provided"]);
    exit;
}

// Get user ID, product ID, and quantity from the JSON data
$userId = $data['user_id'];
$productId = $data['product_id'];
$quantity = $data['quantity'];

// Call the function to update quantity of item in the cart
$result = updateCartItemQuantity($userId, $productId, $quantity);

// Output the result (you can handle it as needed, e.g., return JSON)
echo json_encode($result);

// Close the database connection
$conn->close();
?>
