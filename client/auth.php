<?php
// Ensure session is started before checking authentication
session_start();

/**
 * Checks if the user is authenticated and an admin.
 * Returns true if authenticated and an admin, false otherwise.
 */
function checkAdminAccess() {
    if (!isset($_SESSION['username']) || $_SESSION['admin'] !== 1) {
        header('Location: /login.php'); // Redirect to login page if not authenticated or not an admin
        exit(); // Stop further script execution
    }

}

// Call the function to check admin access
checkAdminAccess();
?>