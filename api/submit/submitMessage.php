<?php
include "../db_connexion.php";
global $conn;
session_start();

// Check if the request method is POST and user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    // Validate POST parameters
    if (!isset($_POST['receiver']) || !isset($_POST['message'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('success' => false, 'message' => 'Missing receiver or message'));
        exit;
    }

    $receiverUsername = $_POST['receiver'];
    $messageText = $_POST['message'];

    // Look for username to get receiver's user ID
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $receiverUsername);
    $stmt->execute();
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the receiver exists
    if (!$receiver) {
        http_response_code(404); // Not found
        echo json_encode(array('success' => false, 'message' => 'Receiver not found'));
        exit;
    }

    try {
        // Insert the message into the database
        $stmt = $conn->prepare('INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (:sender_id, :receiver_id, :message_text)');

        // Make sure the session variable for user ID is set
        if (!isset($_SESSION['user_id'])) {
            throw new PDOException("Sender ID not set in session");
        }

        $stmt->bindParam(':sender_id', $_SESSION['user_id']);
        $stmt->bindParam(':receiver_id', $receiver['id']); // Correct the key
        $stmt->bindParam(':message_text', $messageText);

        $stmt->execute();

        http_response_code(201); // Created
        echo json_encode(array('success' => true, 'message' => 'Message sent successfully'));

    } catch (PDOException $e) {
        http_response_code(500); // Internal server error
        echo $e->getMessage();
        echo json_encode(array('success' => false, 'message' => 'Error sending the message'));
    }
} else {
    // Unauthorized access
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
}
?>
