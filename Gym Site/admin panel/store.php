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

function fetchInventory($conn) {
    $sql = "SELECT * FROM inventory";
    $result = $conn->query($sql);

    $inventory = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $inventory[] = $row;
        }
    }
    return $inventory;
}

function updateStock($conn, $product_id, $stock_change, $reason, $notes) {
    $sql = "UPDATE products SET stock = stock + :stock_change WHERE id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':stock_change', $stock_change);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();

    $sql = "INSERT INTO stock_history (product_id, change_amount, reason, notes) VALUES (:product_id, :change_amount, :reason, :notes)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':change_amount', $stock_change);
    $stmt->bindParam(':reason', $reason);
    $stmt->bindParam(':notes', $notes);
    $stmt->execute();
}

function fetchStockHistory($conn, $product_id) {
    $sql = "SELECT * FROM stock_history WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();

    $history = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $history[] = $row;
        }
    }
    return $history;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_stock'])) {
        $product_id = $_POST['product_id'];
        $stock_change = $_POST['stock_change'];
        $reason = $_POST['reason'];
        $notes = $_POST['notes'];
        updateStock($conn, $product_id, $stock_change, $reason, $notes);
    }
}

$products = fetchProducts($conn);
$categories = fetchCategories($conn);
$inventory = fetchInventory($conn);

?>