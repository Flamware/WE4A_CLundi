<?php
session_start();
include "db_connexion.php";
global $conn;
if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
    //check if profile picture exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //parse user data
    $user = array(
        'username' => $user['username'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'profile_picture' => $user['profile_picture'] ? $user['profile_picture'] : 'assets/default_profile_picture.jpg'
    );
    http_response_code(200);
    echo json_encode(array('success' => true, 'user' => $user));
    exit;
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}