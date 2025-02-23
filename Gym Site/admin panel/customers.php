<?php

require_once '../db/db_config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

function fetchCustomers($conn) {
    $sql = "SELECT * FROM customers";
    $result = $conn->query($sql);

    $customers = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $customers[] = $row;
        }
    }
    return $customers;
}

function fetchCustomerProgress($conn, $customerId) {
    $sql = "SELECT * FROM progress WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
    $stmt->execute();

    $progress = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $progress[] = $row;
        }
    }
    return $progress;
}

function addCustomer($conn, $data) {
    $sql = "INSERT INTO customers (full_name, email, phone, membership_plan, start_date, status, profile_image) VALUES (:full_name, :email, :phone, :membership_plan, :start_date, :status, :profile_image)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function updateCustomer($conn, $data) {
    $sql = "UPDATE customers SET full_name = :full_name, email = :email, phone = :phone, membership_plan = :membership_plan, start_date = :start_date, status = :status, profile_image = :profile_image WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function deleteCustomer($conn, $customerId) {
    $sql = "DELETE FROM customers WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateCustomerGoals($conn, $data) {
    $sql = "INSERT INTO goals (customer_id, weekly_visits, session_duration, weight_goal, target_date) VALUES (:customer_id, :weekly_visits, :session_duration, :weight_goal, :target_date) ON DUPLICATE KEY UPDATE weekly_visits = :weekly_visits, session_duration = :session_duration, weight_goal = :weight_goal, target_date = :target_date";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_customer'])) {
        $data = [
            'full_name' => $_POST['fullName'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'membership_plan' => $_POST['membershipPlan'],
            'start_date' => $_POST['startDate'],
            'status' => $_POST['status'],
            'profile_image' => $_FILES['profileImage']['name']
        ];
        addCustomer($conn, $data);
    } elseif (isset($_POST['update_customer'])) {
        $data = [
            'id' => $_POST['customerId'],
            'full_name' => $_POST['fullName'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'membership_plan' => $_POST['membershipPlan'],
            'start_date' => $_POST['startDate'],
            'status' => $_POST['status'],
            'profile_image' => $_FILES['profileImage']['name']
        ];
        updateCustomer($conn, $data);
    } elseif (isset($_POST['delete_customer'])) {
        $customerId = $_POST['customerId'];
        deleteCustomer($conn, $customerId);
    } elseif (isset($_POST['update_goals'])) {
        $data = [
            'customer_id' => $_POST['customerId'],
            'weekly_visits' => $_POST['weeklyVisits'],
            'session_duration' => $_POST['sessionDuration'],
            'weight_goal' => $_POST['weightGoal'],
            'target_date' => $_POST['targetDate']
        ];
        updateCustomerGoals($conn, $data);
    }
}

$customers = fetchCustomers($conn);
?>