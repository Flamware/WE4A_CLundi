<?php
session_start();
include "../db_connexion.php"; // Database connection
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, return Unauthorized status
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'You must be logged in.'));
    exit;
}

$username = $_SESSION['username']; // Get username from session

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for required parameters
    if (!isset($_POST['story'])) {
        // If 'story' parameter is missing, return Bad Request status
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Story parameter is missing'));
        exit;
    }

    // Retrieve the story content
    $story = $_POST['story'];
    $storyImageFilename = null; // Default value for the image filename

    // Check if an image was uploaded
    if (isset($_FILES['story_image']) && $_FILES['story_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/stories/'; // Directory to save uploaded images
        $uploadFileName = basename($_FILES['story_image']['name']); // Get the file name only
        $targetPath = $uploadDir . $uploadFileName; // Complete file path

        // Check for unique filename (to avoid overwriting existing files)
        if (file_exists($targetPath)) {
            $uploadFileName = time() . '-' . $uploadFileName; // Add a timestamp to ensure uniqueness
            $targetPath = $uploadDir . $uploadFileName; // Update target path
        }

        // Move the uploaded file to the desired location
        if (move_uploaded_file($_FILES['story_image']['tmp_name'], $targetPath)) {
            $storyImageFilename = $uploadFileName; // Save the filename
        } else {
            // If upload failed, return an error
            http_response_code(500);
            echo json_encode(array('success' => false, 'message' => 'Error uploading image'));
            exit;
        }
    }

    // Insert the story content, author, and optional image filename into the database
    try {
        $stmt = $conn->prepare('INSERT INTO stories (content, author, story_image) VALUES (?, ?, ?)');
        $stmt->execute([$story, $username, $storyImageFilename]);
        http_response_code(201); // Created status
        echo json_encode(array('success' => true, 'message' => 'Story submitted successfully'));
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500); // Internal Server Error
        echo json_encode(array('success' => false, 'message' => 'Error submitting the story'));
    }
} else {
    // If method is not POST, return Method Not Allowed status
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
}
?>
