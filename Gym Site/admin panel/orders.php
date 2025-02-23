<?php

require_once '../db/db_config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

function fetchOrders($conn) {
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);

    $orders = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row;
        }
    }
    return $orders;
}

function fetchOrderDetails($conn, $order_id) {
    $sql = "SELECT * FROM order_details WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    $orderDetails = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderDetails[] = $row;
        }
    }
    return $orderDetails;
}

function updateOrderStatus($conn, $order_id, $status, $tracking_number = null, $notes = null) {
    $sql = "UPDATE orders SET status = :status, tracking_number = :tracking_number, notes = :notes WHERE id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':tracking_number', $tracking_number, PDO::PARAM_STR);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $tracking_number = $_POST['tracking_number'] ?? null;
    $notes = $_POST['notes'] ?? null;

    if (updateOrderStatus($conn, $order_id, $status, $tracking_number, $notes)) {
        echo "Order updated successfully.";
    } else {
        echo "Failed to update order.";
    }
}

$orders = fetchOrders($conn);
?>
