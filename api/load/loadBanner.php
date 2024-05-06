<?php
session_start();
include "../db_connexion.php";
global $conn;

// Check if the user is logged in and if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['user_id'])) {
    // Get the user ID from the session
    $username = $_GET['username'] ?? $_SESSION['username'];

    // Fetch the user's profile picture from the database
    $selectQuery = "SELECT banner_picture FROM users WHERE username = :username";
    $statement = $conn->prepare($selectQuery);
    $statement->bindParam(":username", $username);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Return the profile picture URL
    echo json_encode(["success" => true, "banner_picture" => $result['banner_picture']]);
    exit;
}
