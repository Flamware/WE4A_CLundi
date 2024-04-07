<?php
session_start();
include 'db_connexion.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(array('success' => false, 'message' => 'All fields are required'));
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
            exit;
        }

        if (password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            setcookie('user_id', $result['user_id'], time() + 3600, '/');
            // Return the entire cookie in the JSON response
            echo json_encode(array('success' => true, 'username' => $result['username'], 'cookie' => $_COOKIE));
            exit;

        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Error: ' . $e->getMessage()));
        exit;
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
