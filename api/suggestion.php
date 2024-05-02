<?php
/**
 * Suggestion API for DM
 * sources : CLundi.Fr
 */
session_start();
include "db_connexion.php";
global $conn;
// Check if the query parameter is set
if (isset($_GET['query'])) {
    // Fetch users whose username starts with the query
    $stmt = $conn->prepare("SELECT username FROM users WHERE username LIKE :query");
    $stmt->bindValue(':query', $_GET['query'] . '%');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Return the list of users as JSON
    echo json_encode($users);
    exit;
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
