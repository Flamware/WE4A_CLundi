<?php
session_start();
include "db_connexion.php";

global $conn;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT stories.*, COUNT(likes.like_id) AS like_count 
                            FROM stories 
                            LEFT JOIN likes ON stories.id = likes.story_id 
                            GROUP BY stories.id");
    $stmt->execute();
    $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform fetched data into desired JSON structure
    $formattedStories = [];
    foreach ($stories as $story) {
        $formattedStories[] = array(
            'id' => $story['id'],
            'content' => $story['content'],
            'author' => $story['author'],
            'date' => $story['created_at'],
            'like_count' => $story['like_count']
        );
    }
    // Response code
    http_response_code(200);
    echo json_encode($formattedStories);
    exit;
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
