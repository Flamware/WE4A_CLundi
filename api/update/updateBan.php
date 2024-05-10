<?php
/**
 * Update the ban status of a user
 * Method: POST
 * Source: Axel Antunes & CoPilot
 *
 * This file allows an admin to ban or deban a user
 * It requires the username of the user to be banned/debanned
 * It also requires the ban duration and reason
 * It returns a JSON response indicating success or failure
 */
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
$stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
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
elseif ($banDuration === 'permanent') {
    $banEnd = date('Y-m-d H:i:s', strtotime('+100 years'));
}
$isBanned = 1;
//if the user is debanned clear the ban details
if ($banDuration === 'no ban') {
    $banStart = null;
    $banEnd = null;
    $banReason = null;
    $isBanned = 0;
}

// Update the user's ban status in the database
$updateStmt = $conn->prepare("UPDATE users SET is_banned = :isBanned, ban_start = :ban_start, ban_end = :ban_end, ban_reason = :ban_reason WHERE id = :id");
$updateStmt->bindParam(':isBanned', $isBanned, PDO::PARAM_INT);
$updateStmt->bindParam(':ban_start', $banStart);
$updateStmt->bindParam(':ban_end', $banEnd);
$updateStmt->bindParam(':ban_reason', $banReason);
$updateStmt->bindParam(':id', $user['id'], PDO::PARAM_INT);

if ($updateStmt->execute()) {
    http_response_code(200); // Success
    if ($banDuration === 'no ban') {
        echo json_encode(['success' => true, 'message' => 'User debanned successfully']);
    } else {
        echo json_encode(['success' => true, 'message' => 'User banned successfully']);
    }
} else {
    http_response_code(500); // Internal server error
    echo json_encode(['success' => false, 'message' => 'Failed to ban user']);
}
?>
