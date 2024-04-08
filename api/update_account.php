<?php
/*
 * Update account information
 * Method: POST
 * Parameters: email, first_name, last_name
 * What's updated: email, first_name, last_name, updated_at
 * The user must be logged in to update their account information
 *
 */
session_start();
include "db_connexion.php";
global $conn;
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_SESSION['username']; // Fetch username from session
    $updated_at = date('Y-m-d H:i:s');
    //check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Invalid email address'));
        exit;
    }
    //check if first name is valid
    if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Invalid first name'));
        exit;
    }
    //check if last name is valid
    if (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Invalid last name'));
        exit;
    }
    //check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND username != :username");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Email address already in use'));
        exit;
    }
    //update user information
    $stmt = $conn->prepare("UPDATE users SET email = :email, first_name = :first_name, last_name = :last_name, updated_at = :updated_at WHERE username = :username");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':updated_at', $updated_at);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('success' => true, 'message' => 'Account information updated successfully'));
    exit;
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;

}