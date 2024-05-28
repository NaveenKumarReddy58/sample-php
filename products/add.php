<?php
include '../connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    // Check if it's a single product or multiple products
    if (isset($data['name'])) {
        // Single product
        addSingleProduct($data);
    } elseif (is_array($data) && count($data) > 0) {
        // Multiple products
        addMultipleProducts($data);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
    }
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

function addSingleProduct($productData) {
    global $conn;

    // Validate and sanitize the inputs
    $name = $conn->real_escape_string($productData['name']);
    $description = $conn->real_escape_string($productData['description']);
    $price = floatval($productData['price']); // Convert to float
    $sku = $conn->real_escape_string($productData['sku']);
    $category = $conn->real_escape_string($productData['category']);
    $stock_quantity = intval($productData['stock_quantity']); // Convert to integer
    $image_url = $conn->real_escape_string($productData['image_url']);

    // Insert the product data into the database
    $sql = "INSERT INTO products (name, description, price, sku, category, stock_quantity, image_url)
            VALUES ('$name', '$description', $price, '$sku', '$category', $stock_quantity, '$image_url')";

    if ($conn->query($sql) === TRUE) {
        // Product added successfully
        echo json_encode(["status" => "success", "message" => "Product added successfully"]);
    } else {
        // Error inserting product
        echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function addMultipleProducts($productsData) {
    global $conn;

    // Check for duplicate SKUs
    $skus = array();
    foreach ($productsData as $product) {
        $sku = $product['sku'];
        if (in_array($sku, $skus)) {
            // Duplicate SKU found
            echo json_encode(["status" => "error", "message" => "Duplicate SKU '$sku' found"]);
            return;
        }
        $skus[] = $sku;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO products (name, description, price, sku, category, stock_quantity, image_url) VALUES ";

    // Iterate through each product and build the SQL query
    foreach ($productsData as $product) {
        // Validate and sanitize the inputs
        $name = $conn->real_escape_string($product['name']);
        $description = $conn->real_escape_string($product['description']);
        $price = floatval($product['price']); // Convert to float
        $sku = $conn->real_escape_string($product['sku']);
        $category = $conn->real_escape_string($product['category']);
        $stock_quantity = intval($product['stock_quantity']); // Convert to integer
        $image_url = $conn->real_escape_string($product['image_url']);

        // Append values to the SQL query
        $sql .= "('$name', '$description', $price, '$sku', '$category', $stock_quantity, '$image_url'),";
    }

    // Remove the trailing comma
    $sql = rtrim($sql, ',');

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Products added successfully
        echo json_encode(["status" => "success", "message" => "Products added successfully"]);
    } else {
        // Error inserting products
        echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}


$conn->close();
?>
