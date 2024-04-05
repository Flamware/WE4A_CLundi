<?php
session_start();
include "db_connexion.php";
global $conn;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request contains the required parameters
    if (!isset($_POST['story']) || !isset($_POST['username'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Content and username are required'));
        exit;
    }

    // Get the story and username from the request
    $story = $_POST['story'];
    $username = $_POST['username'];

    // Insert the story and username into the database
    try {
        $stmt = $conn->prepare('INSERT INTO stories (content, author) VALUES (?, ?)');
        $stmt->execute([$story, $username]);
        http_response_code(201);
        echo json_encode(array());
    } catch (PDOException $e) {
        // Handle database errors
        error_log('Error submitting the story: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(array('error' => 'Error submitting the story'));
    }
} else {
    // Return method not allowed if request method is not POST
    http_response_code(405);
}
?>