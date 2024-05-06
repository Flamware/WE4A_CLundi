<?php
session_start();
include '../db_connexion.php'; // Assuming this file contains the database connection
global $conn;

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(array('message' => 'You are not an admin. Please log in as an admin to access this page.'));
    exit;
}

// Check if the required parameters are provided via POST
if (isset($_POST['type']) && isset($_POST['id']) && isset($_POST['report_content'])) {
    // Get the parameters from the POST data
    $type = $_POST['type'];
    $id = $_POST['id'];
    $content = $_POST['report_content'];

    // Validate the type parameter to prevent SQL injection
    $validTypes = ['story', 'comment', 'message'];
    if (!in_array($type, $validTypes)) {
        echo json_encode(array('message' => 'Error: Invalid report type.'));
        exit;
    }
    $report_db = $type . '_reports';


    try {
        // Prepare the SQL statement using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO $report_db ({$type}_id, content, `from`) VALUES (:id, :content, :from)");

        if ($stmt) {
            // Bind parameters
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':from', $_SESSION['user_id']);

            // Execute the statement
            $stmt->execute();
            echo json_encode(array('message' => 'Report submitted successfully'));
        } else {
            echo json_encode(array('message' => 'Error: Unable to prepare statement.'));
        }
    } catch (PDOException $e) {
        // Check if it's a duplicate entry error
        if ($e->getCode() === '23000') {
            echo json_encode(array('message' => 'Error: The report has already been submitted.'));
        } else {
            echo json_encode(array('message' => 'Error: ' . $e->getMessage()));
        }
    }
} else {
    echo json_encode(array('message' => 'Error: Missing parameters.'));
}
