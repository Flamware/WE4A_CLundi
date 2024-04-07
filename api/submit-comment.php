<?php

session_start();
include "db_connexion.php";
global $conn;

if (!isset($_SESSION['username'])) {
    // If the user is not logged in, return an error
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    exit;
}
else {
    $username = $_SESSION['username'];
    echo $_SESSION['username'];
}
// Check if the request method is POST and session is active
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {

    // Retrieve data from POST request
    $parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : null;
    $content = isset($_POST['content']) ? $_POST['content'] : null;
    $story_id = isset($_POST['story_id']) ? $_POST['story_id'] : null;
    $author = $_SESSION['username'];

    // Construct SQL statement based on the presence of parent_comment_id
    if ($parent_comment_id == 0 || $parent_comment_id === 'null') {
        // If parent_comment_id is null, it's a comment on a story
        $sql = "INSERT INTO comments (story_id, author, content) VALUES (?, ?, ?)";
    } else {
        // If parent_comment_id is not null, it's a reply to another comment
        $sql = "INSERT INTO comments (story_id, author, content, parent_comment_id) VALUES (?, ?, ?, ?)";
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the statement
    try {
        if ($parent_comment_id === 'null' || $parent_comment_id == 0) {
            $stmt->execute([$story_id, $author, $content]);
        } else {
            $stmt->execute([$story_id, $author, $content, $parent_comment_id]);
        }
        // Return JSON response indicating success
        http_response_code(200); // OK
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        // Return JSON response indicating failure and error message
        http_response_code(500); // Internal Server Error
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    // Return JSON response indicating invalid action or unauthorized access
    http_response_code(401); // Unauthorized
    echo json_encode(array('success' => false, 'message' => 'Unauthorized access or invalid action'));
}
?>
