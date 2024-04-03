<?php
include 'process_admin.php'; 
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the data from the POST request
    $productId = $_GET['productId'];
    $price = $_GET['price'];
    $stock = $_GET['stock'];

    // Connect to the database
    updateProduct($productId, $price, $stock);

} else {
    echo "Invalid request method";
}
?>