<?php
session_start();
require_once '../db/db_config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.html');
    exit();
}

$db = Database::getInstance();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $fitness_goal = $_POST['fitness_goal'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "../assets/images/profiles/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = basename($_FILES["profile_picture"]["name"]);
    } else {
        $profile_picture = $user['profile_picture'];
    }

    $update_query = "UPDATE users SET full_name = ?, email = ?, phone = ?, dob = ?, gender = ?, fitness_goal = ?, profile_picture = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->execute([$full_name, $email, $phone, $dob, $gender, $fitness_goal, $profile_picture, $user_id]);

    header('Location: profile.php');
    exit();
}
?>
