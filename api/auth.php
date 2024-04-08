<?php
session_start();

// Check if the user is authenticated
if (isset($_SESSION['username'])) {
    // User is authenticated, return OK response
    http_response_code(200);
    echo json_encode(array('authenticated' => true));
} else {
    // User is not authenticated, return Unauthorized response
    http_response_code(401);
    echo json_encode(array('authenticated' => false));
}
?>
