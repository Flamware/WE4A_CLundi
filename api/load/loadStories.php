<?php
/**
 * Load stories from the database
 * Method: GET
 * Parameters: story_id (optional), page (optional), query (optional)
 * Source : Estouan Gachelin, Axel Antunes & https://github.com/Flamware/CLundi
 *
 * This file loads stories from the database
 * It returns stories in a JSON format
 * The stories are paginated and can be filtered by a search query
 * Each story includes its comments and like count
 */
session_start();
include '../db_connexion.php'; // Database connection
global $conn;

// Function to fetch paginated stories
function fetchPaginatedStories($page, $storiesPerPage, $search = null) {
    global $conn;

    // Calculate offset for pagination
    $offset = ($page - 1) * $storiesPerPage;

    // Start with the base SQL query
    $sql = "SELECT stories.*, COUNT(likes.id) AS like_count 
            FROM stories 
            LEFT JOIN likes ON stories.id = likes.story_id";

    // If there's a search term, append a WHERE clause and search for author or content , create a LIKE clause
    if ($search) {
        $sql .= " WHERE stories.author LIKE :search OR stories.content LIKE :search OR stories.created_at LIKE :search";
    }

    // Add pagination and order
    $sql .= " GROUP BY stories.id 
             ORDER BY stories.created_at DESC 
             LIMIT :offset, :limit";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $storiesPerPage, PDO::PARAM_INT);

    if ($search) {
        // Create a separate variable for the LIKE clause
        $searchQuery = '%' . $search . '%';
        $stmt->bindParam(':search', $searchQuery, PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function fetchStoryById($storyId) {
    global $conn;

    $stmt = $conn->prepare(
        "SELECT stories.*, COUNT(likes.id) AS like_count 
         FROM stories 
         LEFT JOIN likes ON stories.id = likes.story_id 
         WHERE stories.id = :storyId 
         GROUP BY stories.id"
    );

    // Correct the parameter binding name
    $stmt->bindParam(':storyId', $storyId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

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

// Check the request method and user authentication
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['story_id'])) { // Fetch a specific story by ID
        $story = fetchStoryById(intval($_GET['story_id']));

        if ($story) {
            // Fetch comments for this specific story
            $comments = fetchCommentsByStoryId($story['id']);

            $response = array(
                'success' => true,
                'story' => array(
                    'id' => $story['id'],
                    'content' => $story['content'],
                    'author' => $story['author'],
                    'date' => $story['created_at'],
                    'like_count' => $story['like_count'],
                    'story_image' => $story['story_image'] ?? null,
                    'comments' => $comments // Include comments in the response
                )
            );
            http_response_code(200); // OK status
        } else {
            $response = array(
                'success' => false,
                'message' => 'Story not found'
            );
            http_response_code(404); // Not Found status
        }
        echo json_encode($response);
    } else { // Fetch paginated stories, possibly with a search term
        $storiesPerPage = 10; // Number of stories per page
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $searchTerm = isset($_GET['query']) ? trim($_GET['query']) : null;

        $totalStmt = $conn->query("SELECT COUNT(*) FROM stories");
        $totalStories = $totalStmt->fetchColumn(); // Total number of stories

        // Fetch stories based on pagination and optional search term
        $stories = fetchPaginatedStories($currentPage, $storiesPerPage, $searchTerm);

        // Fetch comments for each story
        $storiesWithComments = array_map(function($story) {
            // Fetch comments for this story
            $comments = fetchCommentsByStoryId($story['id']);

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
            'stories' => $storiesWithComments
        );

        http_response_code(200); // OK status
        echo json_encode($response);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>