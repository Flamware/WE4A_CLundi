<?php
include '../db_connexion.php';
global $conn;
session_start();

// Check if user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get the POST data
$username = $_POST['username'] ?? '';
$banDuration = $_POST['ban_duration'] ?? '';
$banReason = $_POST['ban_reason'] ?? 'No reason given';

// Find the user by username
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404); // User not found
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

// Determine the ban start and end times
$banStart = date('Y-m-d H:i:s'); // Current time
$banEnd = null; // Default to no end time (permanent)

if ($banDuration === '1 day') {
    $banEnd = date('Y-m-d H:i:s', strtotime('+1 day'));
} elseif ($banDuration === '7 days') {
    $banEnd = date('Y-m-d H:i:s', strtotime('+7 days'));
} elseif ($banDuration === '30 days') {
    $banEnd = date('Y-m-d H:i:s', strtotime('+30 days'));
}

// Update the user's ban status in the database
$updateStmt = $conn->prepare("UPDATE users SET is_banned = 1, ban_start = :ban_start, ban_end = :ban_end, ban_reason = :ban_reason WHERE user_id = :user_id");
$updateStmt->bindParam(':ban_start', $banStart);
$updateStmt->bindParam(':ban_end', $banEnd);
$updateStmt->bindParam(':ban_reason', $banReason);
$updateStmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);

if ($updateStmt->execute()) {
    http_response_code(200); // Success
    echo json_encode(['success' => true, 'message' => 'User banned successfully']);
} else {
    http_response_code(500); // Internal server error
    echo json_encode(['success' => false, 'message' => 'Failed to ban user']);
}
?>
