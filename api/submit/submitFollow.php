<?php
/**
 * Follow or unfollow a user
 * Method: POST
 * Source : Estouan Gachelin
 *
 * This file allows a user to follow or unfollow another user
 * It requires the logged-in user's ID and the target user's username
 * It returns a JSON response indicating success or failure
 */

session_start();
include '../db_connexion.php';
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('success' => false, 'message' => 'Vous devez être connecté pour suivre ou ne plus suivre un utilisateur.'));
    exit;
}

// Get the logged-in user's ID and the target user's username
$follower_id = $_SESSION['user_id']; // Logged-in user
$target_username = $_POST['username']; // The user to be followed/unfollowed

// Get the target user's ID from their username
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$target_username]);
$target_id = $stmt->fetchColumn();

if (!$target_id) {
    http_response_code(404); // Not Found
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé.']);
    exit;
}

// Prevent self-following
if ($follower_id == $target_id) {
    http_response_code(400); // Bad request
    echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas vous suivre.']);
    exit;
}

try {
    // Check if a following relationship exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user_following WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$follower_id, $target_id]);

    if ($stmt->fetchColumn() > 0) {
        // If following, then unfollow
        $stmt = $conn->prepare("DELETE FROM user_following WHERE follower_id = ? AND followed_id = ?");
        $stmt->execute([$follower_id, $target_id]);

        echo json_encode(['success' => true, 'isFollowing' => false, 'message' => 'Vous ne suivez plus cet utilisateur.']);
    } else {
        // If not following, then follow
        $stmt = $conn->prepare("INSERT INTO user_following (follower_id, followed_id) VALUES (?, ?)");
        $stmt->execute([$follower_id, $target_id]);

        echo json_encode(['success' => true, 'isFollowing' => true, 'message' => 'Vous suivez maintenant cet utilisateur.']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
