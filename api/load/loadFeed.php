<?php
/**
 * Load stories from authors followed by the current user
 * Method: GET
 * Parameters: page
 * Source : CoPilot, ChatGPT & Axel Antunes
 *
 * This file loads stories from authors followed by the current user with pagination.
 * It returns the stories and comments in JSON format.
 */
session_start();
include '../db_connexion.php'; // Database connection
global $conn;

// Fetch the list of authors followed by the current user
function getFollowedAuthors($conn, $followerId) {
    $stmt = $conn->prepare("
        SELECT u.username 
        FROM user_following uf 
        INNER JOIN users u ON uf.followed_id = u.id 
        WHERE uf.follower_id = :follower_id
    ");
    $stmt->bindParam(':follower_id', $followerId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch stories from the given list of authors with pagination
function loadStoriesFromAuthors($conn, $authorNames, $page, $storiesPerPage) {
    // Calculate offset for pagination
    $offset = ($page - 1) * $storiesPerPage;

    // Prepare SQL statement with positional parameters for authors, limit, and offset
    $placeholders = str_repeat('?,', count($authorNames) - 1) . '?';

    $stmt = $conn->prepare("
        SELECT stories.*, COUNT(likes.id) AS like_count 
        FROM stories 
        LEFT JOIN likes ON stories.id = likes.story_id 
        WHERE stories.author IN ($placeholders) 
        GROUP BY stories.id 
        ORDER BY stories.created_at DESC 
        LIMIT ? OFFSET ?
    ");

    // Bind author names
    for ($i = 0; $i < count($authorNames); $i++) {
        $stmt->bindValue($i + 1, $authorNames[$i]); // 1-based index for placeholders
    }

    // Bind limit and offset
    $stmt->bindValue(count($authorNames) + 1, $storiesPerPage, PDO::PARAM_INT);
    $stmt->bindValue(count($authorNames) + 2, $offset, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Return the fetched data
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Fetch comments for the given stories
function loadCommentsForStories($conn, $stories) {
    $stmt = $conn->prepare("
        SELECT comments.*, COUNT(likes.id) AS like_count 
        FROM comments 
        LEFT JOIN likes ON comments.id = likes.comment_id 
        WHERE comments.story_id = :story_id 
        GROUP BY comments.id
    ");

    $comments = [];
    foreach ($stories as $story) {
        $stmt->bindParam(':story_id', $story['id'], PDO::PARAM_INT);
        $stmt->execute();
        $comments[$story['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $comments;
}

// Format stories for output
function formatStories($stories) {
    return array_map(function($story) {
        return [
            'id' => $story['id'],
            'content' => $story['content'],
            'author' => $story['author'],
            'created_at' => $story['created_at'],
            'like_count' => $story['like_count'],
            'story_image' => $story['story_image'] ?? null,
        ];
    }, $stories);
}

// Format comments for output
function formatComments($comments) {
    $formatted = [];
    foreach ($comments as $storyId => $commentList) {
        foreach ($commentList as $comment) {
            $formatted[] = [
                'id' => $comment['id'],
                'story_id' => $comment['story_id'],
                'content' => $comment['content'],
                'author' => $comment['author'],
                'created_at' => $comment['created_at'],
                'like_count' => $comment['like_count'],
            ];
        }
    }

    return $formatted;
}

// Main logic for handling the GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['success' => false, 'message' => 'Vous devez être connecté.']);
        exit;
    }

    $followerId = $_SESSION['user_id'];
    $followedAuthors = getFollowedAuthors($conn, $followerId);

    if (empty($followedAuthors)) {
        http_response_code(200); // OK, but no followed authors
        echo json_encode(['success' => true, 'stories' => [], 'comments' => []]);
        exit;
    }

    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $pageSize = 10;

    $stories = loadStoriesFromAuthors($conn, $followedAuthors, $page, $pageSize);

    if ($stories === false) {
        http_response_code(500); // Server error
        echo json_encode(['success' => false, 'message' => 'Erreur lors du chargement des histoires.']);
        exit;
    }

    $comments = loadCommentsForStories($conn, $stories);

    $formattedStories = formatStories($stories);
    $formattedComments = formatComments($comments);

    $totalStories = count($formattedStories);
    $hasMorePages = $totalStories === $pageSize;

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'stories' => $formattedStories,
        'comments' => $formattedComments,
        'has_more_pages' => $hasMorePages,
    ]);

} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Methode non autorisée.']);
}

?>
