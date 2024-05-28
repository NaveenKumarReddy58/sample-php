<?php
include '../connection.php'; // Include your database connection file

// Function to get cart list with count based on user
function getCartList($userId) {
    global $conn;

    // Validate user ID
    if (!is_numeric($userId)) {
        return ["status" => "error", "message" => "Invalid user ID"];
    }

    // Prepare SQL statement to get cart list and count
    $sql = "SELECT c.product_id, p.name AS product_name, p.price, c.quantity
            FROM cart c
            INNER JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    // Execute the statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Initialize variables for total count and cart items
        $totalCount = 0;
        $cartList = [];

        // Fetch cart items and calculate total count
        while ($row = $result->fetch_assoc()) {
            $cartList[] = [
                "product_id" => $row['product_id'],
                "product_name" => $row['product_name'],
                "price" => $row['price'],
                "quantity" => $row['quantity']
            ];
            $totalCount += $row['quantity'];
        }

        return ["status" => "success", "total_count" => $totalCount, "cart_list" => $cartList];
    } else {
        return ["status" => "error", "message" => "Error fetching cart list: " . $conn->error];
    }

    // Close the statement
    $stmt->close();
}

// Get raw JSON data sent from the client
$json_data = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json_data, true);

// Check if "user_id" is provided in the JSON data
if (!isset($data['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User ID not provided"]);
    exit;
}

// Get user ID from the JSON data
$userId = $data['user_id'];

// Call the function to get cart list with count based on user
$result = getCartList($userId);

// Output the result (you can handle it as needed, e.g., return JSON)
echo json_encode($result);

// Close the database connection
$conn->close();
?>
