<?php
/**
 * Update notifications
 * Method: POST
 * Source: Estouan Gachelin
 *
 * This file handles the server-side logic to update notifications
 * It marks all notifications as read for the logged-in user
 * It sends a JSON response indicating the success or failure of the process
 */
session_start();
include '../db_connexion.php';
global $conn;
// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    // Mark all notifications as read
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
}
?>

