<?php
session_start();
include "../db_connexion.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_SESSION['username'])) {
   // Check if the request contains the required parameters
    if (!isset($_POST['story_id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Story ID is required'));
        exit;
    }

    // Get the story ID from the request
    $story_id = $_POST['story_id'];

    // Check if the story exists
    $stmt = $conn->prepare('SELECT * FROM stories WHERE id = ?');
    $stmt->execute([$story_id]);
    $story = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$story) {
        http_response_code(404);
        echo json_encode(array('error' => 'Story not found'));
        exit;
    }
    //check if the user is the author of the story or an admin
    if ($_SESSION['username'] !== $story['author'] && $_SESSION['admin'] !== 1) {
        http_response_code(403);
        echo json_encode(array('error' => 'You are not authorized to delete this story'));
        exit;
    }
    // Delete the story
    try {
        $stmt = $conn->prepare('DELETE FROM stories WHERE id = ?');
        $stmt->execute([$story_id]);
        http_response_code(200); // Change status code to 200 for successful deletion
        echo json_encode(array('success' => true)); // Respond with success message
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500);
        echo json_encode(array('error' => 'Error deleting the story'.$e->getMessage()));
    }
} else {
    // Return method not allowed if request method is not POST
    http_response_code(405);
}
?>
