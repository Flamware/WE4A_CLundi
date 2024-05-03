<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
    echo json_encode(['success' => false, 'message' => 'Access denied. You are not an admin.']);
    exit;
}

// Check if a username is provided in the GET request
if (!isset($_GET['username'])) {
    echo json_encode(['success' => false, 'message' => 'Username not provided.']);
    exit;
}

require '../db_connexion.php';
global $conn;

$username = $_GET['username'];

// Fetch the user ID using a prepared statement
$stmt = $conn->prepare("SELECT user_id, reported FROM users WHERE username = ?");
$stmt->execute([$username]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user exists
if (!$userData) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$user_id = $userData['user_id'];
$reported = $userData['reported'];

// If the user has not been reported, return a message indicating no reports found
if ($reported == 0) {
    echo json_encode(['success' => true, 'message' => 'No reports found for this user.']);
    exit;
}
// Check ban status in the database
$stmt = $conn->prepare("SELECT is_banned , ban_start, ban_end FROM users WHERE username = ?");
$stmt->execute([$username]);
$banStatus = $stmt->fetch(PDO::FETCH_ASSOC);


// Initialize arrays to store reported stories, comments, and messages
$reportedStories = [];
$reportedComments = [];
$reportedMessages = [];

// Fetch reported stories using a prepared statement
$stmt = $conn->prepare("SELECT * FROM story_reports WHERE story_id IN (SELECT id FROM stories WHERE author = ?)");
$stmt->execute([$username]);
$reportedStories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch reported comments
$stmt = $conn->prepare("SELECT * FROM comment_reports WHERE comment_id IN (SELECT comment_id FROM comments WHERE author = ?)");
$stmt->execute([$username]);
$reportedComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch reported messages
$stmt = $conn->prepare("SELECT * FROM message_reports WHERE message_id IN (SELECT message_id FROM messages WHERE sender_id = ?)");
$stmt->execute([$user_id]);
$reportedMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as a JSON response
echo json_encode([
    'success' => true,
    'message' => 'Reports found.',
    'banStatus' => $banStatus,
    'reportedStories' => $reportedStories,
    'reportedComments' => $reportedComments,
    'reportedMessages' => $reportedMessages,
]);
