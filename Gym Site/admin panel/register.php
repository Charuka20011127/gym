<?php
require_once '../db/db_config.php';

$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];  
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$username = $_POST['username'];
$password = $_POST['password'];
$userType = $_POST['userType'];

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, date_of_birth, gender, username, password, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fullName, $email, $phoneNumber, $dob, $gender, $username, $password, $userType]);

    echo "Registration Successful...";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
