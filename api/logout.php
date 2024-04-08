<?php
session_start();

// Destroy the session
session_destroy();

// Send a JSON response indicating successful logout
echo json_encode(array('success' => true));
?>
