<?php
include "db_connexion.php";
session_start();
global $conn;


// Check if the request method is GET and if the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $storyCount = 0;
    $commentCount = 0;
    $likeCount = 0;
    $commentCountPerStory = 0;

    try {
        // Get the number of stories
        $sql = "SELECT COUNT(*) as count FROM stories WHERE author = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $storyCount = $result['count'];
        }

        // Get the number of comments
        $sql = "SELECT COUNT(*) as count FROM comments WHERE author = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $commentCount = $result['count'];
        }

        // Get the number of likes using user_id
        $sql = "SELECT COUNT(*) as count FROM likes WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $likeCount = $result['count'];
        }

        // Get the number of comments per story
        $sql = "SELECT id FROM stories WHERE author = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $stories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if ($stories) {
            // Get the number of comments per story
            foreach ($stories as $storyId) {
                $sql = "SELECT COUNT(*) as count FROM comments WHERE story_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$storyId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $commentCountPerStory += $result['count'];
                }
            }
        }
        // get the number of like the user has received on his stories
        $sql = "SELECT COUNT(*) as count FROM likes WHERE story_id IN (SELECT id FROM stories WHERE author = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $likeReceived = $result['count'];
        }

        // get the number of like the user has received on his comments
        $sql = "SELECT COUNT(*) as count FROM likes WHERE comment_id IN (SELECT id FROM comments WHERE author = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $likeReceived += $result['count'];
        }


        // Encode the data as JSON
        echo json_encode([
            'storyCount' => $storyCount,
            'commentCount' => $commentCount,
            'likeCount' => $likeCount,
            'likeReceived' => $likeReceived,
            'commentCountPerStory' => $commentCountPerStory
        ]);
    } catch (PDOException $e) {
        // Handle any database errors
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
