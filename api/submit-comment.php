<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include "db_connexion.php";
    global $conn;

    // Retrieve data from POST request
    $parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : null;
    $content = isset($_POST['content']) ? $_POST['content'] : null;
    $author = isset($_POST['author']) ? $_POST['author'] : null;
    $story_id = isset($_POST['story_id']) ? $_POST['story_id'] : null;

    // Construct SQL statement based on the presence of parent_comment_id
    if ($parent_comment_id === 'null') {
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
        if ($parent_comment_id === 'null') {
            $stmt->execute([$story_id, $author, $content]);
        } else {
            $stmt->execute([$story_id, $author, $content, $parent_comment_id]);
        }
        // Return JSON response indicating success
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        // Return JSON response indicating failure and error message
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    // Return JSON response indicating invalid action
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
}
?>
