<?php
session_start();
include "db_connexion.php";
global $conn;
if ($_SERVER["REQUEST_METHOD"] == "GET"&& isset($_SESSION['username'])){
    $stmt = $conn->prepare("SELECT * FROM comments");
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform fetched data into desired JSON structure
    $formattedComments = [];
    foreach ($comments as $comment) {
        $formattedComments[] = array(
            'id' => $comment['comment_id'],
            'story_id' => $comment['story_id'],
            'parent_comment_id' => $comment['parent_comment_id'],
            'content' => $comment['content'],
            'author' => $comment['author'],
            'created_at' => $comment['created_at']
        );
    }

    echo json_encode($formattedComments);
    exit;
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
