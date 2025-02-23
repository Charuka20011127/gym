<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once '../db/db_config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->execute([$user_id]);
$user = $user_query->fetch(PDO::FETCH_ASSOC);

$dashboard_data = [];
if ($user['user_type'] == 'member') {
    $dashboard_data['workout_plan'] = 'Strength Training';
    $dashboard_data['meal_plan'] = 'Weight Loss Diet';
    $dashboard_data['profile_completion'] = 75;
} elseif ($user['user_type'] == 'owner') {
    $dashboard_data['total_trainers'] = 12;
    $dashboard_data['active_members'] = 245;
    $dashboard_data['monthly_revenue'] = 12450;
    $dashboard_data['store_items'] = 86;
} elseif ($user['user_type'] == 'trainer') {
    $dashboard_data['specialization'] = 'Weight Training, CrossFit';
    $dashboard_data['experience'] = 5; 
    $dashboard_data['certifications'] = 'ACE Certified, CrossFit Level 2';
} elseif ($user['user_type'] == 'seller') {
    $dashboard_data['total_products'] = 124;
    $dashboard_data['orders'] = 47;
    $dashboard_data['revenue'] = 12450;
}
?>
