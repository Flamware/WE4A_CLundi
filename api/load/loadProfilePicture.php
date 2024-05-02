<?php
/*
 * This file is responsible for loading the profile picture of the currently logged in user.
 * The user must be logged in to access this information.
 * Source : https://github.com/Flamware/CLundi
 */
session_start();
include "../db_connexion.php";
global $conn;

if (isset($_GET['author'])) {
    // Fetch the author's profile picture path
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = :author");
    $stmt->bindParam(':author', $_GET['author']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // if the user has a profile picture, return the path
    if ($user && $user['profile_picture']) {
        http_response_code(200);
        echo $user['profile_picture'];
        exit;
    } else {
        // if the user does not have a profile picture, return the default path
        http_response_code(200);
        echo 'default_profile_picture.png';
        exit;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
    // Fetch the user's profile picture path
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // if the user has a profile picture, return the path
    if ($user && $user['profile_picture']) {
        http_response_code(200);
        echo json_encode(array('success' => true, 'profile_picture' => $user['profile_picture']));
        exit;
    } else {
        // if the user does not have a profile picture, return the default path
        http_response_code(200);
        echo json_encode(array('success' => true, 'profile_picture' => 'default_profile_picture.png'));
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
