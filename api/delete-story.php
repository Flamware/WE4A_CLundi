<?php
include 'db_connexion.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Delete the story
    try {
        $stmt = $conn->prepare('DELETE FROM stories WHERE id = ?');
        $stmt->execute([$story_id]);
        http_response_code(200); // Change status code to 200 for successful deletion
        echo json_encode(array('success' => true)); // Respond with success message
    } catch (PDOException $e) {
        // Handle database errors
        error_log('Error deleting the story: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(array('error' => 'Error deleting the story'));
    }
} else {
    // Return method not allowed if request method is not POST
    http_response_code(405);
}
?>
