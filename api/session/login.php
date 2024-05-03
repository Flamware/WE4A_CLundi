<?php

session_start();
include "../db_connexion.php";
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
            http_response_code(401); // Unauthorized
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
            exit;
        }

        // Check if user is banned
        if ($result['is_banned'] == 1) {
            $banStart = strtotime($result['ban_start']);
            $banEnd = strtotime($result['ban_end']);
            $now = time();

            if ($banEnd > 0) {
                $remainingTime = $banEnd - $now;

                if ($remainingTime > 0) {
                    // User is still banned, calculate remaining time
                    $hoursLeft = floor($remainingTime / 3600);
                    $minutesLeft = floor(($remainingTime % 3600) / 60);
                    $secondsLeft = $remainingTime % 60;

                    http_response_code(403); // Forbidden
                    echo json_encode(array(
                        'success' => false,
                        'message' => "You are banned. Remaining time: {$hoursLeft}h {$minutesLeft}m {$secondsLeft}s."
                    ));
                    exit;
                }
            } else {
                // Permanent ban
                http_response_code(403); // Forbidden
                echo json_encode(array('success' => false, 'message' => 'You are permanently banned.'));
                exit;
            }
        }

        // If the user is not banned or the ban has expired, verify the password
        if (password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['admin'] = $result['is_admin'];
            $_SESSION['banned'] = 0;

            // If the ban expired, lift it
            if ($result['is_banned'] == 1 && $banEnd <= $now) {
                $stmt = $conn->prepare("UPDATE users SET is_banned = 0, ban_start = NULL, ban_end = NULL WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $result['user_id']);
                $stmt->execute();
            }

            // Return successful login response
            http_response_code(201); // Created
            echo json_encode(array('success' => true, 'message' => 'Login successful'));
            exit;
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal server error
        echo json_encode(array('success' => false, 'message' => 'Database error'));
        exit;
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
    exit;
}
