<?php
// Check if the API path is already defined to avoid redefining
if (!defined('API_PATH')) {
    // Determine the protocol based on whether HTTPS is on or off
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

    // Get the server host (e.g., localhost, domain.com, etc.)
    $host = $_SERVER['HTTP_HOST'];

    // Optionally, add a dynamic base path if your server has a context path (e.g., if it's not at the root)
    $basePath = '/api'; // Adjust as needed for your setup

    // Define the API path
    define('API_PATH', $protocol . '://' . $host . $basePath);
}
?>
<script>
    var apiPath = '<?php echo API_PATH; ?>';
</script>
