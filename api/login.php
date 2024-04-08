<?php

session_start();
include 'db_connexion.php';
global $conn;

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'All fields are required'));
        exit;
    }

    try {
        // Prepare SQL statement to select user by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if (!$result) {
            // User not found
            http_response_code(401); // Unauthorized
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
            exit;
        }

        // Verify password
        if (password_verify($password, $result['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            // Set cookie
            setcookie('user_id', $result['user_id'], time() + 3600, '/');

            // Return JSON response with success and user information
            http_response_code(201); // Created
            echo json_encode(array('success' => true, 'message' => 'Login successful'));
            exit;
        } else {
            // Invalid password
            http_response_code(401); // Unauthorized
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
            exit;
        }
    } catch (PDOException $e) {
        // Database error
        http_response_code(500); // Internal server error
        echo json_encode(array('success' => false, 'message' => 'Database error'));
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
    exit;
}
?>
