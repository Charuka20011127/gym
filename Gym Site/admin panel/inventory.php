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

function addProduct($conn, $name, $category, $price, $stock, $description, $image) {
    $sql = "INSERT INTO products (name, category, price, stock, description, image) VALUES (:name, :category, :price, :stock, :description, :image)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    return $stmt->execute();
}

function updateProduct($conn, $id, $name, $category, $price, $stock, $description, $image) {
    $sql = "UPDATE products SET name = :name, category = :category, price = :price, stock = :stock, description = :description, image = :image WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    return $stmt->execute();
}

function deleteProduct($conn, $id) {
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $target = "../assets/images/" . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            addProduct($conn, $name, $category, $price, $stock, $description, $image);
        }
    } elseif (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $target = "../assets/images/" . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            updateProduct($conn, $id, $name, $category, $price, $stock, $description, $image);
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        deleteProduct($conn, $id);
    }
}

$products = fetchProducts($conn);
$categories = fetchCategories($conn);
?>