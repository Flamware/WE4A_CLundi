<?php
session_start();
require '../db_connexion.php';
global $conn;

if (!isset($_SESSION['username'])){
    http_response_code(401);
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('error' => 'Invalid request method'));
    exit();
}
if (!isset($_POST['username'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Missing username'));
    exit();
}
if ($_POST['username'] === $_SESSION['username']) {
    http_response_code(400);
    echo json_encode(array('error' => 'Cannot unfollow yourself'));
    exit();
}
$username = $_POST['username'];
try {
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute(array($username));
    $user_id = $stmt->fetchColumn();
    if (!$user_id) {
        http_response_code(404);
        echo json_encode(array('error' => 'User not found'));
        exit();
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Error fetching user'.$e->getMessage()));
    exit();
}
$following = $_SESSION['user_id'];
try {
    $stmt = $conn->prepare('DELETE FROM user_following WHERE follower_id = ? AND followed_id = ?');
    $stmt->execute(array($following, $user_id));
    echo json_encode(array('success' => true));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Error deleting follow'.$e->getMessage()));
}
?>

