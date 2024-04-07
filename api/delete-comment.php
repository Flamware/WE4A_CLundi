<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
include 'db_connexion.php'; // Assuming this file establishes the PDO connection
global $conn;

$comment_id = $_POST['comment_id'];
$sql = "DELETE FROM comments WHERE comment_id = :comment_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
$stmt->execute();
$stmt->closeCursor(); // Close cursor instead of close()

// Respond with a success status code (200)
http_response_code(200);
// echo request url path
echo $_SERVER['REQUEST_URI'];
} else {
    // Respond with a "Method Not Allowed" status code (405)
    http_response_code(405);
    exit('Method Not Allowed');
}
?>