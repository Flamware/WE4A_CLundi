<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clundidb";

try {
    // Create a PDO connection with persistent option enabled
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::ATTR_PERSISTENT => true));

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If connection fails, throw an exception
    throw new Exception("Connection failed: " . $e->getMessage());
}
?>
