<?php
session_start();
include '../db_connexion.php';
global $conn;
include '../../conf.php';
// Function to fetch the story image filename by story ID
function fetchStoryImageFilename($storyId) {
    global $conn;

    $stmt = $conn->prepare(
        "SELECT story_image
         FROM stories 
         WHERE id = :storyId"
    );
    $stmt->bindParam(':storyId', $storyId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Ensure the user is authenticated and the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
    if (isset($_GET['story_id'])) { // Check if the story ID is provided
        $storyId = intval($_GET['story_id']);
        $imageFilename = fetchStoryImageFilename($storyId);

        if ($imageFilename) {
            // Define the path to the image
            $imagePath = API_PATH . '/uploads/stories/' . $imageFilename;
            echo $imagePath;
            if (file_exists($imagePath)) { // Check if the file exists
                // Determine the content type based on the file extension
                $fileInfo = pathinfo($imagePath);
                $contentType = mime_content_type($imagePath);

                // Set headers and output the image
                header('Content-Type: ' . $contentType);
                header('Content-Length: ' . filesize($imagePath));
                readfile($imagePath); // Output the image
                exit;
            } else {
                http_response_code(404); // Not Found
                echo 'Image file not found.';
            }
        } else {
            http_response_code(404); // Not Found
            echo 'No image associated with this story.';
        }
    } else {
        http_response_code(400); // Bad Request
        echo 'Story ID is required.';
    }
} else {
    http_response_code(403); // Forbidden
    echo 'Unauthorized access or invalid request method.';
}
