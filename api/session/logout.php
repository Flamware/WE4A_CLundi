<?php
/**
 * Load comments
 * Method: GET
 * Source : Axel Antunes
 *
 * This file handles the logout server-side logic
 * It destroys the session and sends a JSON response indicating successful logout
 */

session_start();

// Destroy the session
session_destroy();

// Send a JSON response indicating successful logout
echo json_encode(array('success' => true));
?>
