<?php
include "db_connexion.php";
global $conn;
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $stmt = $conn->prepare("SELECT * FROM stories");
    $stmt->execute();
    $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform fetched data into desired JSON structure
    $formattedStories = [];
    foreach ($stories as $story) {
        $formattedStories[] = array(
            'id' => $story['id'],
            'content' => $story['content'],
            'author' => $story['author'],
            'date' => $story['created_at']
        );
    }

    echo json_encode($formattedStories);
    exit;
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    exit;
}
?>
