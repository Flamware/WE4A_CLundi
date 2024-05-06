<?php
session_start();
include "../db_connexion.php";
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit(); // Ensure the script doesn't continue
}

$user_id = $_SESSION['user_id'];

// Fetch all notifications for the user, ordered by date
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the count of unseen notifications (where is_read is 0)
$stmt_count = $conn->prepare("SELECT COUNT(*) AS unseen_count FROM notifications WHERE user_id = :user_id AND is_read = 0");
$stmt_count->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_count->execute();
$unseen_count_result = $stmt_count->fetch(PDO::FETCH_ASSOC);

$unseen_count = $unseen_count_result['unseen_count'] ?? 0; // If not found, default to 0

// Return the notifications and the unseen count
http_response_code(200); // OK
echo json_encode([
    'success' => true,
    'unseen_count' => $unseen_count, // Include the unseen count
    'notifications' => $notifications // Include the notifications
]);

exit(); // Ensure the script doesn't continue
?>
