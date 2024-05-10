<?php
/**
 * Make user an admin
 * Method: POST
 * Source : Estouan Gachelin & CoPilot
 *
 * This file handles the server-side logic to make a user an admin
 * It receives the username of the user to make an admin
 * It checks if the user is already an admin
 * If the user is not an admin, it makes the user an admin
 * It sends a JSON response indicating the success or failure of the process
 */
session_start();
include "../db_connexion.php";
global $conn;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])&& $_SESSION['admin'] === 1) {
    if (!isset($_POST['username'])) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Il manque des informations'));
        exit;
    }
    // fetch the admin
    try {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $stmt = $conn->prepare('SELECT is_admin FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$admin) {
            http_response_code(404);
            echo json_encode(array('success' => false, 'message' => 'Utilisateur non trouvé'));
            exit;
        }
        if ($admin['is_admin'] === 1) {
            // send a message saying that the user is already an admin
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Cet utilisateur est déjà un admin'));
            exit;
        }
        // make the user an admin
        $stmt = $conn->prepare('UPDATE users SET is_admin = 1 WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Cet utilisateur est maintenant un admin'));
            exit;
        }
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error making the user an admin'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error making the user an admin', 'error' => $e->getMessage()));
        error_log('PDOException: ' . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Acces non autorisé'));
    exit;
}
?>
