<?php
include "../db_connexion.php"; // Make sure this file includes your database connection

session_start();
global $conn;
// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Fetch DMs where the current user is either the sender or receiver
    $stmt = $conn->prepare("SELECT * FROM messages WHERE sender_id = :user_id OR receiver_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define an array to store discussions
    $discussions = [];
    // Loop through the fetched messages
    foreach ($messages as $message) {
        // Determine the ID of the other user involved in the discussion
        $other_user_id = ($message['sender_id'] == $user_id) ? $message['receiver_id'] : $message['sender_id'];

        // Check if a discussion with the other user already exists
        $discussion_exists = false;
        foreach ($discussions as &$discussion) {
            if ($discussion['user_id'] == $other_user_id) {
                $discussion['messages'][] = [
                    'message_id' => $message['message_id'],
                    'message_text' => $message['message_text'],
                    'sent_at' => $message['sent_at'],
                    'is_sender' => ($message['sender_id'] == $user_id)
                ];
                $discussion_exists = true;
                break;
            }
        }

        // If the discussion doesn't exist, create a new one
        if (!$discussion_exists) {
            $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $other_user_id);
            $stmt->execute();
            $other_user = $stmt->fetch(PDO::FETCH_ASSOC);

            $discussions[] = [
                'user_id' => $other_user_id,
                'receiver' => $other_user['username'],
                'sender' => $_SESSION['username'],
                'messages' => [
                    [
                        'message_id' => $message['message_id'],
                        'message_text' => $message['message_text'],
                        'sent_at' => $message['sent_at'],
                        'is_sender' => ($message['sender_id'] == $user_id),
                    ]
                ]
            ];
        }
    }

    // Return the discussions as JSON
    http_response_code(200);
    echo json_encode($discussions);
    exit;
} else {
    // Return error message if the user is not logged in
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    exit;
}
?>
