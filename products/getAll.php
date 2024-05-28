<?php
include '../connection.php';

// Get total count of products
$count_sql = "SELECT COUNT(*) AS total_count FROM products";
$count_result = $conn->query($count_sql);
$total_count = 0;
if ($count_result && $count_result->num_rows > 0) {
    $total_count = $count_result->fetch_assoc()['total_count'];
}

// Select all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array to store product data
    $products = array();

    // Fetch each row and add it to the products array
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    // Return the products array along with the total count as JSON
    echo json_encode(["status" => "success", "total_count" => $total_count, "products" => $products]);
} else {
    // No products found
    echo json_encode(["status" => "error", "message" => "No products found"]);
}

$conn->close();
?>
