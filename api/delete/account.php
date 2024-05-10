<?php
/**
 * Delete the user's account
 * Method: DELETE
 * Parameters: username (optional)
 * The username parameter is only required if the user is an admin and wants to delete another user's account.
 */

session_start();
include '../db_connexion.php';
global $conn;
// Check if the user is logged in
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour effectuer cette action.']);
    exit;
}
// Check which account to delete
if(isset($_GET['username'])) {
    // Check if the user is an admin
    if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
        echo json_encode(['success' => false, 'message' => 'Accès refusé. Vous n\'êtes pas un administrateur.']);
        exit;
    }
    // Fetch the user ID using a prepared statement
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_GET['username']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the user exists
    if(!$userData) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
        exit;
    }
    $user_id = $userData['id'];
} else {
    $user_id = $_SESSION['user_id'];
}
// Delete the user's account
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user_id]);
session_destroy();
echo json_encode(['success' => true, 'message' => 'Votre compte a été supprimé avec succès.']);
exit;
?>

