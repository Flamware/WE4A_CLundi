<?php
include "../db_connexion.php";
global $conn;
session_start();

// Check if the request method is POST and user is logged in
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    //look for username with the receiver_id
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_POST['receiver']);
    $stmt->execute();
    $receiver_id = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the receiver exists
    if(!$receiver_id) {
        http_response_code(404);
        echo json_encode(array('success' => false, 'message' => 'Receiver not found'));
        exit;
    }
    //insert the message into the database
    try {
        $stmt = $conn->prepare('INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (:sender_id, :receiver_id, :message_text)');
        $stmt->bindParam(':sender_id', $_SESSION['user_id']);
        $stmt->bindParam(':receiver_id', $receiver_id['user_id']);
        $stmt->bindParam(':message_text', $_POST['message']);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('success' => true, 'message' => 'Message sent successfully'));
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error sending the message'));
    }
} else {
    // Return error message if the user is not logged in
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
}
?>