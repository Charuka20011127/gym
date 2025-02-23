<?php
session_start();
require_once '../db/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    $db = Database::getInstance();
    $conn = $db->getConnection();

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND user_type = ?");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $userType);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (password_verify($password, $result['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['userType'] = $userType;

                switch ($userType) {
                    case 'gym-goer':
                        header("Location: gym_goer_dashboard.php");
                        break;
                    case 'trainer':
                        header("Location: trainer_dashboard.php");
                        break;
                    case 'seller':
                        header("Location: seller_dashboard.php");
                        break;
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                }
            } else {
                echo "Invalid Password!";
            }
        } else {
            echo "Invalid Username or User Type!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid Request Method!";
}
?>
