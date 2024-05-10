<?php
/**
 * Delete a story report
 * Method: POST
 * Parameters: report_id
 * Source : CoPilot & Axel Antunes
 *
 * This file deletes a story report
 * The user must be an admin to delete a report
 */
session_start();
include "../db_connexion.php";
global $conn;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    if (!isset($_POST['report_id'])) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Missing report ID'));
        exit;
    }
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
        http_response_code(403);
        echo json_encode(array('success' => false, 'message' => 'Access denied. You are not an admin.'));
        exit;
    }
    // try to delete the report
    try {
        $report_id = filter_var($_POST['report_id'], FILTER_SANITIZE_NUMBER_INT);
        $stmt = $conn->prepare('DELETE FROM story_reports WHERE id = ?');
        $stmt->execute([$report_id]);
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Report deleted successfully'));
            exit;
        }
        http_response_code(404);
        echo json_encode(array('success' => false, 'message' => 'Report not found'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Error deleting the report', 'error' => $e->getMessage()));
        error_log('PDOException: ' . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
    exit;
}