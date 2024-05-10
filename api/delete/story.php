<?php
/**
 * Delete a story
 * Method: POST
 * Parameters: story_id
 * Source : CoPilot & Axel Antunes
 *
 * This file deletes a story if the current user is the author or an admin
 * The story must not be reported to be deleted unless the user is an admin
 */
session_start();
include "../db_connexion.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    if (!isset($_POST['story_id'])) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Missing story ID'));
        exit;
    }

    // Sanitize and extract the story ID
    $story_id = filter_var($_POST['story_id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if the story exists
    $stmt = $conn->prepare('SELECT * FROM stories WHERE id = ?');
    $stmt->execute([$story_id]);
    $story = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$story) {
        http_response_code(404);
        echo json_encode(array('success' => false, 'message' => 'Story not found'));
        exit;
    }

    // Check if the story is reported
    $stmt = $conn->prepare('SELECT COUNT(*) FROM story_reports WHERE story_id = ?');
    $stmt->execute([$story_id]);
    $reportCount = $stmt->fetchColumn();

    if ($reportCount > 0&& $_SESSION['admin'] !== 1) {
        http_response_code(403);
        echo json_encode(array('success' => false, 'message' => 'Cannot delete story as it has been reported'));
        exit;
    }

    // Check if the user is the author or an admin
    if ($_SESSION['username'] !== $story['author'] && $_SESSION['admin'] !== 1) {
        http_response_code(403);
        echo json_encode(array('success' => false, 'message' => 'Not authorized to delete the story'));
        exit;
    }

    // Attempt to delete the story
    try {
        $conn->beginTransaction();
        $stmt = $conn->prepare('DELETE FROM stories WHERE id = ?');
        $stmt->execute([$story_id]);
        $conn->commit();

        http_response_code(200);
        echo json_encode(array('success' => true, 'message' => 'Story deleted successfully'));
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error deleting the story', 'error' => $e->getMessage()));
        error_log('PDOException: ' . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
}
