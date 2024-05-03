<?php
session_start();
include '../db_connexion.php'; // Database connection
global $conn;

// Function to fetch paginated stories
function fetchPaginatedStories($page, $storiesPerPage) {
    global $conn;

    // Calculate offset for pagination
    $offset = ($page - 1) * $storiesPerPage;

    // Prepare SQL statement with pagination and likes count
    $stmt = $conn->prepare(
        "SELECT stories.*, COUNT(likes.like_id) AS like_count 
         FROM stories 
         LEFT JOIN likes ON stories.id = likes.story_id 
         GROUP BY stories.id 
         ORDER BY stories.created_at DESC 
         LIMIT :offset, :limit"
    );
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $storiesPerPage, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch a specific story by its ID
function fetchStoryById($storyId) {
    global $conn;

    $stmt = $conn->prepare(
        "SELECT stories.*, COUNT(likes.like_id) AS like_count 
         FROM stories 
         LEFT JOIN likes ON stories.id = likes.story_id 
         WHERE stories.id = :storyId 
         GROUP BY stories.id"
    );
    $stmt->bindParam(':id', $storyId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check the request method and user authentication
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION['username'])) { // Ensure user is authenticated
        if (isset($_GET['story_id'])) { // Fetch a specific story by ID
            $story = fetchStoryById(intval($_GET['story_id']));

            if ($story) {
                $response = array(
                    'success' => true,
                    'story' => array(
                        'id' => $story['id'],
                        'content' => $story['content'],
                        'author' => $story['author'],
                        'date' => $story['created_at'],
                        'like_count' => $story['like_count'],
                        'story_image' => $story['story_image'] ?? null // Optional image filename
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
        } else { // Fetch paginated stories
            $storiesPerPage = 10; // Number of stories per page
            $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

            $totalStmt = $conn->query("SELECT COUNT(*) FROM stories");
            $totalStories = $totalStmt->fetchColumn(); // Total number of stories

            $stories = fetchPaginatedStories($currentPage, $storiesPerPage);

            $formattedStories = array_map(function($story) {
                return array(
                    'success' => true,
                    'id' => $story['id'],
                    'content' => $story['content'],
                    'author' => $story['author'],
                    'date' => $story['created_at'],
                    'like_count' => $story['like_count'],
                    'story_image' => $story['story_image'] ?? null // Optional image filename
                );
            }, $stories);

            $response = array(
                'success' => true,
                'total_stories' => $totalStories,
                'stories' => $formattedStories
            );

            http_response_code(200); // OK status
            echo json_encode($response);
        }
    } else {
        // Unauthorized access
        http_response_code(403); // Forbidden
        echo json_encode(array('success' => false, 'message' => 'Unauthorized access. Please log in.'));
    }
} else {
    // Incorrect HTTP method
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>
