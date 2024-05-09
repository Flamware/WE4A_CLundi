<?php
session_start();
include '../db_connexion.php'; // Database connection
global $conn;

// Function to fetch paginated stories sorted by likes
function fetchPaginatedStories($page, $storiesPerPage, $search = null) {
    global $conn;

    // Calculate offset for pagination
    $offset = ($page - 1) * $storiesPerPage;

    // SQL query to fetch paginated stories, sorted by like count
    // If a search term is provided, include a WHERE clause to filter by it
    $sql = "SELECT stories.*, COUNT(likes.id) AS like_count 
            FROM stories 
            LEFT JOIN likes ON stories.id = likes.story_id 
            " . ($search ? "WHERE stories.content LIKE :search " : "") . "
            GROUP BY stories.id 
            ORDER BY like_count DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);

    if ($search) {
        // Use wildcard for partial matching in SQL
        $searchParam = "%" . $search . "%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }

    $stmt->bindParam(':limit', $storiesPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the stories
}

// Function to fetch comments by story ID
function fetchCommentsByStoryId($storyId) {
    global $conn;

    // SQL query to fetch comments and count the likes for each comment
    $stmt = $conn->prepare(
        "SELECT comments.*, COUNT(likes.id) AS like_count 
         FROM comments 
         LEFT JOIN likes ON comments.id = likes.comment_id 
         WHERE comments.story_id = :story_id 
         GROUP BY comments.id 
         ORDER BY comments.created_at ASC"
    );
    $stmt->bindParam(':story_id', $storyId, PDO::PARAM_INT); // Bind the story ID
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return comments with like count
}

// Check the request method
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $storiesPerPage = 10; // Number of stories per page
    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Ensure current page is at least 1
    $searchTerm = isset($_GET['query']) ? trim($_GET['query']) : null;

    // Get total number of stories (with possible search term)
    $totalQuery = "SELECT COUNT(DISTINCT stories.id) 
                   FROM stories 
                   LEFT JOIN likes ON stories.id = likes.story_id
                   " . ($searchTerm ? "WHERE stories.content LIKE :search" : "");
    $totalStmt = $conn->prepare($totalQuery);

    if ($searchTerm) {
        $totalStmt->bindParam(':search', "%" . $searchTerm . "%", PDO::PARAM_STR); // Bind wildcard search term
    }

    $totalStmt->execute();
    $totalStories = $totalStmt->fetchColumn(); // Get total stories count

    // Fetch paginated stories
    $stories = fetchPaginatedStories($currentPage, $storiesPerPage, $searchTerm); // Get stories

    // Fetch comments for each story
    $storiesWithComments = array_map(function($story) {
        $comments = fetchCommentsByStoryId($story['id']); // Fetch comments
        return array(
            'id' => $story['id'],
            'content' => $story['content'],
            'author' => $story['author'],
            'date' => $story['created_at'],
            'like_count' => $story['like_count'],
            'story_image' => $story['story_image'] ?? null,
            'comments' => $comments // Include comments in the story data
        );
    }, $stories);

    $response = array(
        'success' => true,
        'total_stories' => $totalStories,
        'stories' => $storiesWithComments,
        'current_page' => $currentPage,
        'stories_per_page' => $storiesPerPage
    );

    http_response_code(200); // OK status
    echo json_encode($response); // Return response as JSON
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
