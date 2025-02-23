<?php
require_once '../db/db_config.php';


$db = Database::getInstance();
$conn = $db->getConnection();

$sql = "SELECT * FROM meal_plans";
$result = $conn->query($sql);

$meal_plans = [];
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $meal_plans[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dietary_preference = $_POST['dietary_preference'];
    $goal = $_POST['goal'];
    $allergies = $_POST['allergies'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO custom_meal_requests (dietary_preference, goal, allergies, notes) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$dietary_preference, $goal, $allergies, $notes])) {
        $message = "Custom meal plan request submitted successfully!";
    } else {
        $message = "Error: " . $stmt->errorInfo()[2];
    }
}

$conn = null;
?>
