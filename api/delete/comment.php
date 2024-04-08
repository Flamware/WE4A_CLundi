<?php
session_start();
include "../db_connexion.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    // Initialize a flag to track the success of the deletion
    $deleted = false;

    //Try deleting the comment from the database if it belongs to the user
    try {
        $stmt = $conn->prepare('DELETE FROM comments WHERE comment_id = ? AND author = ?');
        $stmt->execute([$_POST['comment_id'], $_SESSION['username']]);

        // Check if the comment was deleted successfully
        if ($stmt->rowCount() > 0) {
            $deleted = true;
        }
    } catch (PDOException $e) {
        // Handle database errors
        error_log('Error deleting the comment: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error deleting the comment'));
        exit();
    }

    // Respond based on whether the comment was successfully deleted
    if ($deleted) {
        http_response_code(200);
        echo json_encode(array('success' => true, 'message' => 'Comment deleted successfully'));
        exit();
    } else {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Not authorized to delete the comment or comment not found'));
        exit();
    }
} else {
    // Return method not allowed if request method is not POST
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
    exit();
}
?>
