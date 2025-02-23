<?php

require_once '../db/db_config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

function fetchWorkoutPlans($conn) {
    $sql = "SELECT * FROM workout_plans";
    $result = $conn->query($sql);

    $workoutPlans = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $workoutPlans[] = $row;
        }
    }
    return $workoutPlans;
}

function fetchFeaturedPlans($conn) {
    $sql = "SELECT * FROM workout_plans WHERE featured = 1";
    $result = $conn->query($sql);

    $featuredPlans = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $featuredPlans[] = $row;
        }
    }
    return $featuredPlans;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_custom_plan'])) {
    $fitness_level = $_POST['fitness_level'];
    $primary_goal = $_POST['primary_goal'];
    $equipment = $_POST['equipment'];
    $training_days = $_POST['training_days'];
    $medical_conditions = $_POST['medical_conditions'];
    $additional_notes = $_POST['additional_notes'];

    $sql = "INSERT INTO custom_plan_requests (fitness_level, primary_goal, equipment, training_days, medical_conditions, additional_notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fitness_level, $primary_goal, $equipment, $training_days, $medical_conditions, $additional_notes]);

    echo "Custom workout plan request submitted successfully!";
}

$workoutPlans = fetchWorkoutPlans($conn);
$featuredPlans = fetchFeaturedPlans($conn);
?>