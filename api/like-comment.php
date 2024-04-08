<?php
/*
 * This script allows users to like a comment
 */
session_start();
global $conn;
include 'db_connexion.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_SESSION['username'])){
        //
    } else {
        // User is not authenticated
        http_response_code(401);
        echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method Not Allowed'));
}
