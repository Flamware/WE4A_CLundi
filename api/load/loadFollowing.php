<?php
/**
 * Load the list of users the current user is following
 * Method: GET
 * Parameters: username
 * Source : CoPilot, Axel Antunes & Estouan Gachelin
 *
 * This file loads the list of users the current user is following from the database
 * It returns the list of usernames
 */
require '../db_connexion.php';
session_start();
global $conn;
// Check if the request method is GET and if the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['username'])) {
    //try fetching the list of users the current user is following
    $username = $_GET['username']??$_SESSION['username'];
    try{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user_id = $result['id'];
            $sql = "SELECT followed_id FROM user_following WHERE follower_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id]);
            $following = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($following) {
                // fetch usernames of the users the current user is following
                $sql = "SELECT username FROM users WHERE id IN (" . implode(',', array_fill(0, count($following), '?')) . ")";
                $stmt = $conn->prepare($sql);
                $stmt->execute($following);
                $following = $stmt->fetchAll(PDO::FETCH_COLUMN);
                // json encode success and return the list of users the current user is following
                echo json_encode(['success' => true, 'following' => $following]);
            } else {
                // json encode success and return an empty array
                echo json_encode(['success' => true, 'following' => []]);
            }

        }
    } catch (PDOException $e) {
        // If an exception occurs, return an error message
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'An error occurred while fetching the list of users the current user is following']);
    }
} else {
    // If the request method is not GET or the user is not logged in, return an error message
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized request']);
}
?>
