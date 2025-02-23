<?php

require_once '../db/db_config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

function fetchProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    $products = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
    }
    return $products;
}

function fetchCategories($conn) {
    $sql = "SELECT DISTINCT category FROM products";
    $result = $conn->query($sql);

    $categories = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['category'];
        }
    }
    return $categories;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    echo "Product ID: $product_id, Quantity: $quantity";
}

$products = fetchProducts($conn);
$categories = fetchCategories($conn);
?>
