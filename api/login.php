<?php
session_start();
include 'db_connexion.php'; // Assuming this file includes database connection
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(array('success' => false, 'message' => 'Username and password are required'));
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user !== false) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                echo json_encode(array('success' => true, 'message' => 'Login successful', 'username' => $user['username']));
                exit;
            } else {
                echo json_encode(array('success' => false, 'message' => 'Incorrect password'));
                exit;
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'User not found'));
            exit;
        }
    } catch(PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Error: ' . $e->getMessage()));
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>
