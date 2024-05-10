<?php
/**
 * Deletes a comment if the current user is the author or an admin
 * The comment must not be reported to be deleted
 * Method: POST
 * Parameters: comment_id
 * Source : CoPilot & Clundi.fr
 */
session_start();
include "../db_connexion.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
    exit();
}

if (!isset($_SESSION['username']) || !isset($_POST['comment_id'])) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Missing required information'));
    exit();
}

$comment_id = (int) $_POST['comment_id'];
$author = $_SESSION['username'];

try {
    // Check if the comment is reported and if the current user is the author or an admin
    $stmt = $conn->prepare(
        'SELECT COUNT(*) AS reportCount, (author = ?) AS is_author
         FROM comments 
         LEFT JOIN comment_reports ON comments.id = comment_reports.comment_id 
         WHERE comments.id = ?'
    );

    $stmt->execute([$author, $comment_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['reportCount'] > 0 && $_SESSION['admin'] !== 1) {
        http_response_code(403);
        echo json_encode(array('success' => false, 'message' => 'Cannot delete comment as it has been reported'));
        exit();
    }

    if ($result['is_author'] || $_SESSION['admin'] === 1) {
        $delete_stmt = $conn->prepare('DELETE FROM comments WHERE id = ?');
        $delete_stmt->execute([$comment_id]);

        if ($delete_stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Comment deleted successfully'));
            exit();
        }
    }

    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'Not authorized to delete the comment'));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'message' => 'Error deleting the comment'));
    error_log('PDOException: ' . $e->getMessage()); // Log detailed error
}
?>
