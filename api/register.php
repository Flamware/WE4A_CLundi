<?php
include 'db_connexion.php';
global $conn;

if($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode(array('success' => false, 'message' => 'All fields are required'));
        exit;
    } else if ($password !== $confirmPassword) {
        echo json_encode(array('success' => false, 'message' => 'Passwords do not match'));
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(array('success' => false, 'message' => 'Username or email already exists'));
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        // Send a success response
        echo json_encode(array('success' => true));
        exit;
    } catch(PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Error: ' . $e->getMessage()));
        exit;
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}