<?php
include "db_connexion.php";
global $conn;
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $stmt = $conn->prepare("SELECT * FROM stories");
    $stmt->execute();
    $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($stories);
    exit;
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
