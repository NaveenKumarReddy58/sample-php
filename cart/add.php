<?php
include '../connection.php'; // Include your database connection file

// Function to check if the product already exists in the cart for the user
function productExistsInCart($userId, $productId) {
    global $conn;

    // Prepare SQL statement to check if the product exists in the cart for the user
    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a row is returned, the product already exists in the cart
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to add product to the cart
function addToCart($userId, $productId, $quantity) {
    global $conn;

    // Validate user ID, product ID, and quantity
    if (!is_numeric($userId) || !is_numeric($productId) || !is_numeric($quantity) || $quantity <= 0) {
        return ["status" => "error", "message" => "Invalid user ID, product ID, or quantity"];
    }

    // Check if the product exists in the database
    $product_check_query = "SELECT id FROM products WHERE id=$productId";
    $result = $conn->query($product_check_query);
    if ($result->num_rows == 0) {
        return ["status" => "error", "message" => "Product with ID $productId does not exist"];
    }

    // Check if the user exists in the database
    $user_check_query = "SELECT id FROM users WHERE id=$userId";
    $result = $conn->query($user_check_query);
    if ($result->num_rows == 0) {
        return ["status" => "error", "message" => "User with ID $userId does not exist"];
    }

    // Check if the product already exists in the cart for the user
    if (productExistsInCart($userId, $productId)) {
        return ["status" => "error", "message" => "Product already exists in the cart"];
    }

    // Prepare SQL statement to insert into cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");

    // Bind parameters and execute the statement
    $stmt->bind_param("iii", $userId, $productId, $quantity);
    if ($stmt->execute()) {
        return ["status" => "success", "message" => "Product added to cart"];
    } else {
        return ["status" => "error", "message" => "Error adding product to cart: " . $conn->error];
    }

    // Close the statement
    $stmt->close();
}

// Get the raw POST data sent by Postman
$json_data = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json_data, true);

// Check if the JSON data is valid
if ($data === null || !isset($data['user_id']) || !isset($data['product_id']) || !isset($data['quantity'])) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON data or missing required fields"]);
    exit;
}

// Call the function to add product to the cart
$result = addToCart($data['user_id'], $data['product_id'], $data['quantity']);

// Output the result (you can handle it as needed, e.g., return JSON)
echo json_encode($result);

// Close the database connection
$conn->close();
?>
