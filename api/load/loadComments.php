<?php
/**
 * Load comments
 * Method: GET
 * Source : Estouan Gachelin & Axel Antunes
 *
 * This file loads all comments from the database
 * It returns the comments in a JSON format
 * The comments are ordered from the most recent to the oldest
 */
session_start();
include "../db_connexion.php";

global $conn;
if ($_SERVER["REQUEST_METHOD"] == "GET" ) {
    $stmt = $conn->prepare("SELECT comments.*, COUNT(likes.id) AS like_count 
                            FROM comments 
                            LEFT JOIN likes ON comments.id = likes.comment_id 
                            GROUP BY comments.id");
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform fetched data into desired JSON structure
    $formattedComments = [];
    foreach ($comments as $comment) {
        $formattedComments[] = array(
            'id' => $comment['id'],
            'story_id' => $comment['story_id'],
            'parent_comment_id' => $comment['parent_comment_id'],
            'content' => $comment['content'],
            'author' => $comment['author'],
            'created_at' => $comment['created_at'],
            'like_count' => $comment['like_count']
        );
    }
    // Response code
    // return them the most recent to the oldest
    $formattedComments = array_reverse($formattedComments);
    http_response_code(200);
    echo json_encode(array('success' => true, 'comments' => $formattedComments));
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
