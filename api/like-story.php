<?php
session_start();
include 'db_connexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // Assuming you have a function to retrieve user ID from username
        $storyId = $_POST['id'];

        if (!hasUserLikedStory($username, $storyId)) {
            // User has not liked the story, so like it
            likeStory($username, $storyId);
        } else {
            // User has already liked the story
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'You have already liked this story'));
            exit;
        }
    } else {
        // User is not authenticated
        http_response_code(401);
        echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
    exit;
}

function hasUserLikedStory($userId, $storyId)
{
    // Try to perform a SELECT query on clundidb.user_likes table to check if the user has liked the story
    // Return true if the user has liked the story, false otherwise
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM user_likes WHERE user_id = ? AND story_id = ?");
        $stmt->execute([$userId, $storyId]);


        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => $e->getMessage(), 'query' => $stmt->queryString));
        exit;
    }
}

function likeStory($userId, $storyId)
{
    // Insert a new row into the user_likes table to indicate that the user has liked the story
    // Perform an INSERT query into your user_likes table with the user_id and story_id
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO user_likes (user_id, story_id) VALUES (?, ?)");
        $stmt->execute([$userId, $storyId]);
        http_response_code(201);
        echo json_encode(array('success' => true, 'message' => 'Story liked successfully'));
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => $e->getMessage(), 'query' => $stmt->queryString, 'params' => [$userId, $storyId]));
        exit;
    }
}
?>
