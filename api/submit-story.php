<?php
include "db_connexion.php";
global $conn;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request contains the required parameters
    if (!isset($_POST['story'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Content is required'));
        exit;
    }

    // Get the story from the request
    $story = $_POST['story'];

    // Insert the story into the database
    try {
        $stmt = $conn->prepare('INSERT INTO stories (content) VALUES (?)');
        $stmt->execute([$story]);
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
