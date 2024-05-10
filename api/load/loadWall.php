<?php
include "../db_connexion.php";
session_start();

function loadStoriesFromWall($conn, $author){
    $stmt = $conn->prepare("SELECT stories.*, COUNT(likes.id) AS like_count 
                            FROM stories 
                            LEFT JOIN likes ON stories.id = likes.story_id 
                            WHERE stories.author = :author
                            GROUP BY stories.id");
    $stmt->bindParam(':author', $author);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function loadCommentsForStories($conn, $stories){
    $stmt = $conn->prepare("SELECT comments.*, COUNT(likes.id) AS like_count 
                            FROM comments 
                            LEFT JOIN likes ON comments.id = likes.comment_id 
                            WHERE comments.story_id = :story_id
                            GROUP BY comments.id");
    $comments = [];
    foreach ($stories as $story) {
        $stmt->bindParam(':story_id', $story['id']);
        $stmt->execute();
        $comments[$story['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $comments;
}

function isUserFollowed($conn, $follower_id, $followed_id){
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user_following WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$follower_id, $followed_id]);
    return $stmt->fetchColumn() > 0;
}

function formatStories($stories){
    $formattedStories = [];
    foreach ($stories as $story) {
        $formattedStories[] = array(
            'id' => $story['id'],
            'content' => $story['content'],
            'author' => $story['author'],
            'created_at' => $story['created_at'],
            'like_count' => $story['like_count'],
            'story_image' => $story['story_image']
        );
    }
    return $formattedStories;
}

function formatComments($comments){
    $formattedComments = [];
    foreach ($comments as $story_id => $commentList) {
        foreach ($commentList as $comment) {
            $formattedComments[] = array(
                'id' => $comment['id'],
                'story_id' => $comment['story_id'],
                'parent_comment_id' => $comment['parent_comment_id'],
                'content' => $comment['content'],
                'author' => $comment['author'],
                'created_at' => $comment['created_at'],
                'like_count' => $comment['like_count'],
            );
        }
    }
    return $formattedComments;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['username'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Username parameter is missing.']);
        exit();
    }

    $author = $_GET['username'];

    // Validate the user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$author]);
    $author_id = $stmt->fetchColumn();

    if (!$author_id) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit();
    }

    $is_followed = false;

    if (isset($_SESSION['user_id'])) {
        $follower_id = $_SESSION['user_id'];
        $is_followed = isUserFollowed($conn, $follower_id, $author_id);
    }

    // Load stories from the wall of the specified user
    $stories = loadStoriesFromWall($conn, $author);

    if (!is_array($stories)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error loading stories.']);
        exit();
    }

    // Load comments for the stories
    $comments = loadCommentsForStories($conn, $stories);

    // Format the stories and comments
    $formattedStories = formatStories($stories);
    $formattedComments = formatComments($comments);

    // Return the formatted data with the follow status as JSON response
    http_response_code(200); // OK
    echo json_encode([
        'success' => true,
        'message' => 'Stories and comments loaded successfully.',
        'stories' => $formattedStories,
        'comments' => $formattedComments,
        'is_followed' => $is_followed
    ]);
    exit();

} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}
