<?php
session_start();
include "db_connexion.php";
global $conn;

if (!isset($_SESSION['username'])) {
    // If the user is not logged in, return an error
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    exit;
}
else {
    $username = $_SESSION['username'];
}
// Now you have the username from the cookie, you can use it to retrieve the session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the request contains the required parameters
    if(!isset($_POST['story'])) {
        // If 'story' parameter is missing, return an error
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Story parameter is missing'));
        exit;
    }

    // Get the story from the request
    $story = $_POST['story'];

    // Insert the story and username into the database
    try {
        $stmt = $conn->prepare('INSERT INTO stories (content, author) VALUES (?, ?)');
        $stmt->execute([$story, $username]);
        http_response_code(201);
        echo json_encode(array('success' => true, 'message' => 'Story submitted successfully'));
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error submitting the story'));
    }
} else {
    // Return method not allowed if request method is not POST
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
}
?>
