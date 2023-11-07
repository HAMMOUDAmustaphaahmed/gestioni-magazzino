<?php
// Include the database connection
require_once 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];

    // Prepare the SQL statement to delete the product
    $sql = "DELETE FROM products WHERE id = $productId";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // Product deleted successfully, redirect to the products.php page
        header('Location: products.php');
        exit();
    } else {
        // If an error occurs, you can handle it accordingly
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
