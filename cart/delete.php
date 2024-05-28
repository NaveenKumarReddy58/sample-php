<?php
include '../connection.php'; // Include your database connection file

// Function to delete item from the cart
function deleteCartItem($userId, $productId) {
    global $conn;

    // Validate user ID and product ID
    if (!is_numeric($userId) || !is_numeric($productId)) {
        return ["status" => "error", "message" => "Invalid user ID or product ID"];
    }

    // Prepare SQL statement to delete item from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);

    // Execute the statement
    if ($stmt->execute()) {
        return ["status" => "success", "message" => "Item deleted from cart"];
    } else {
        return ["status" => "error", "message" => "Error deleting item from cart: " . $conn->error];
    }

    // Close the statement
    $stmt->close();
}

// Get raw JSON data sent from Postman
$json_data = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json_data, true);

// Check if "user_id" and "product_id" are provided in the JSON data
if (!isset($data['user_id']) || !isset($data['product_id'])) {
    echo json_encode(["status" => "error", "message" => "User ID or product ID not provided"]);
    exit;
}

// Get user ID and product ID from the JSON data
$userId = $data['user_id'];
$productId = $data['product_id'];

// Call the function to delete item from the cart
$result = deleteCartItem($userId, $productId);

// Output the result (you can handle it as needed, e.g., return JSON)
echo json_encode($result);

// Close the database connection
$conn->close();
?>
