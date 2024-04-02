<?php
include "db_connexion.php";
global $conn;
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $stmt = $conn->prepare("SELECT * FROM comments");
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform fetched data into desired JSON structure
    $formattedComments = [];
    foreach ($comments as $comment) {
        $formattedComments[] = array(
            'id' => $comment['id'],
            'storyId' => $comment['storyId'],
            'parentCommentId' => $comment['parentCommentId'],
            'content' => $comment['content'],
            'author' => $comment['author'],
            'date' => $comment['created_at']
        );
    }

    echo json_encode($formattedComments);
    exit;
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
