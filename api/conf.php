<?php
// Check if the API path is already defined
if (!defined('API_PATH')) {
    // Define the API path dynamically
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    define('API_PATH', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/api');
}
// Define path for the client
define('CLIENT_PATH', 'http://localhost/client');
// Define the database connection details
?>
