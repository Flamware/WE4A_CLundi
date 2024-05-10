<?php
/**
 * Delete a notification
 * Method: POST
 * Parameters: notification_id
 * Source : CoPilot & Axel Antunes
 */
session_start();
include '../db_connexion.php';
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get the user ID and notification ID
$user_id = $_SESSION['user_id'];
$notification_id = $_POST['notification_id'] ?? '';
// try to delete the notification
try {
    $stmt = $conn->prepare('DELETE FROM notifications WHERE id = ? AND user_id = ?');
    $stmt->execute([$notification_id, $user_id]);

    // Check if the notification was deleted successfully
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Notification deleted successfully']);
        exit();
    } else {
        http_response_code(400);
        // Start with debug logging
        echo json_encode(['success' => false, 'message' => 'Notification not found id : ' . $notification_id.' user_id : '.$user_id]);
        exit();
    }
} catch (PDOException $e) {
    // Handle database errors
    error_log('Error deleting the notification: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error deleting the notification']);
    exit();
}
?>