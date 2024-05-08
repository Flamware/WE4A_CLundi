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
    $messageImagePath = null; // Default value for the image path

    // Handle the image upload if it exists
    if (isset($_FILES['message_image']) && $_FILES['message_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/messages/'; // Directory for message images
        $uploadFileName = basename($_FILES['message_image']['name']); // Get the original file name
        $targetPath = $uploadDir . $uploadFileName; // Complete file path

        // Ensure unique filename
        if (file_exists($targetPath)) {
            $uploadFileName = time() . '-' . $uploadFileName; // Append timestamp
            $targetPath = $uploadDir . $uploadFileName; // Update path
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['message_image']['tmp_name'], $targetPath)) {
            http_response_code(500); // Internal server error
            echo json_encode(array('success' => false, 'message' => 'Error uploading image'));
            exit;
        }

        $messageImagePath = $uploadFileName; // Store the filename for later use
    }

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
        // Insert the message into the database, including the image path
        $stmt = $conn->prepare(
            'INSERT INTO messages (sender_id, receiver_id, message_text, message_image) VALUES (:sender_id, :receiver_id, :message_text, :message_image)'
        );

        // Check if the session variable for user ID is set
        if (!isset($_SESSION['user_id'])) {
            throw new PDOException("Sender ID not set in session");
        }

        $stmt->bindParam(':sender_id', $_SESSION['user_id']); // Sender ID
        $stmt->bindParam(':receiver_id', $receiver['id']); // Receiver ID
        $stmt->bindParam(':message_text', $messageText); // Message text
        $stmt->bindParam(':message_image', $messageImagePath); // Image path (can be NULL)

        $stmt->execute();

        http_response_code(201); // Created
        echo json_encode(array('success' => true, 'message' => 'Message sent successfully'));

    } catch (PDOException $e) {
        http_response_code(500); // Internal server error
        echo json_encode(array('success' => false, 'message' => 'Error sending the message'));
    }
} else {
    // Unauthorized access
    http_response_code(401); // Unauthorized
    echo json_encode(array('success' => false, 'message' => 'Unauthorized access'));
}
?>
