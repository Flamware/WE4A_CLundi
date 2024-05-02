<?php
include "../db_connexion.php";

function loadStoriesFromWall($conn, $author){
    $stmt = $conn->prepare("SELECT stories.*, COUNT(likes.like_id) AS like_count 
                            FROM stories 
                            LEFT JOIN likes ON stories.id = likes.story_id 
                            WHERE stories.author = :author
                            GROUP BY stories.id");
    $stmt->bindParam(':author', $author);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function loadCommentsForStories($conn, $stories){
    $stmt = $conn->prepare("SELECT comments.*, COUNT(likes.like_id) AS like_count 
                            FROM comments 
                            LEFT JOIN likes ON comments.comment_id = likes.comment_id 
                            WHERE comments.story_id = :story_id
                            GROUP BY comments.comment_id");
    $comments = [];
    foreach ($stories as $story) {
        $stmt->bindParam(':story_id', $story['id']);
        $stmt->execute();
        $comments[$story['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $comments;
}

function formatStories($stories){
    $formattedStories = [];
    foreach ($stories as $story) {
        $formattedStories[] = array(
            'id' => $story['id'],
            'content' => $story['content'],
            'author' => $story['author'],
            'created_at' => $story['created_at'],
            'like_count' => $story['like_count']
        );
    }
    return $formattedStories;
}

function formatComments($comments){
    $formattedComments = [];
    foreach ($comments as $comment) {
        foreach ($comment as $c) {
            $formattedComments[] = array(
                'id' => $c['comment_id'],
                'story_id' => $c['story_id'],
                'parent_comment_id' => $c['parent_comment_id'],
                'content' => $c['content'],
                'author' => $c['author'],
                'created_at' => $c['created_at'],
                'like_count' => $c['like_count']
            );
        }
    }
    return $formattedComments;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Database connection
    global $conn;

    // Check if username parameter is provided
    if(isset($_GET['username'])) {
        $author = $_GET['username'];
    } else {
        // If no username parameter is provided, use the session username
        if(isset($_SESSION['username'])) {
            $author = $_SESSION['username'];
        } else {
            // If no username is set in the session, return an error response
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'No user logged in.'));
            exit;
        }
    }

    // Load stories from the wall of the specified user
    $stories = loadStoriesFromWall($conn, $author);

    if (!is_array($stories)) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => $stories));
        exit;
    } else {
        // Load comments for the stories
        $comments = loadCommentsForStories($conn, $stories);

        // Format the stories and comments
        $formattedStories = formatStories($stories);
        $formattedComments = formatComments($comments);

        // Return the formatted data as JSON response
        http_response_code(200);
        echo json_encode(array('success' => true, 'stories' => $formattedStories, 'comments' => $formattedComments));
        exit;
    }
} else {
    // If the request method is not GET, return an error response
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
    exit;
}

?>// Path: api/load/loadWall.php