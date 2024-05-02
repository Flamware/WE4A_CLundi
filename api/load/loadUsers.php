<?php
include '../db_connexion.php';
global $conn;
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['user_id'])) {
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 3;
    $offset = ($page - 1) * $limit;

    // Count total number of users
    $countQuery = "SELECT COUNT(*) AS total FROM users";
    $countResult = $conn->query($countQuery);
    $totalCount = $countResult->fetchColumn();

    // Fetch users for the current page
    $sql = "SELECT username FROM users LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return users and total count as JSON response
    echo json_encode(array('users' => $users, 'totalCount' => $totalCount));
}
?>