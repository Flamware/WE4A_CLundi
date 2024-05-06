<?php
/*
 * This file is responsible for loading the profile picture of the currently logged in user.
 * The user must be logged in to access this information.
 * Source : https://github.com/Flamware/CLundi
 */
session_start();
include "../db_connexion.php";
global $conn;

// Define a function to return a JSON response with a given HTTP status code
function sendJsonResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

if (isset($_GET['author'])) {
    // Fetch the author's profile picture path
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = :author");
    $stmt->bindParam(':author', $_GET['author']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the user has a profile picture, return the path
    if ($user && !empty($user['profile_picture'])) {
        sendJsonResponse(200, ['profile_picture' => $user['profile_picture']]);
    } else {
        // If the user does not have a profile picture, return an empty string
        sendJsonResponse(200, ['profile_picture' => '']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['username'])) {
    // Fetch the current user's profile picture path
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['profile_picture'])) {
        sendJsonResponse(200, ['profile_picture' => $user['profile_picture']]);
    } else {
        sendJsonResponse(200, ['profile_picture' => '']);
    }
} else {
    sendJsonResponse(400, ['error' => 'Invalid action']);
}
?>
