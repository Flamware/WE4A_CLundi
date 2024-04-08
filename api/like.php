<?php
include 'db_connexion.php'; // Assuming this file includes your PDO database connection
include 'auth.php'; // Include the auth.php file to check if the user is logged in
global $conn;
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a session or some way to identify the user
    $user_id = $_SESSION['user_id']; // Example, get user ID from session

    // Assuming you receive the liked item ID and type from the POST request
    $liked_item_id = $_POST['id'];
    $liked_item_type = $_POST['type'];

    // Check if the user has already liked the post
    $query_check_like = "SELECT like_id FROM likes WHERE user_id = :user_id AND ";
    if ($liked_item_type == 'story') {
        $query_check_like .= "story_id = :liked_item_id AND liked_item_type = 'story'";
    } elseif ($liked_item_type == 'comment') {
        $query_check_like .= "comment_id = :liked_item_id AND liked_item_type = 'comment'";
    }
    $stmt_check_like = $conn->prepare($query_check_like);
    $stmt_check_like->bindParam(':user_id', $user_id);
    $stmt_check_like->bindParam(':liked_item_id', $liked_item_id);
    $stmt_check_like->execute();
    $existing_like = $stmt_check_like->fetch(PDO::FETCH_ASSOC);

    if ($existing_like) {
        // User has already liked the post, delete the like
        $query_delete_like = "DELETE FROM likes WHERE like_id = :like_id";
        $stmt_delete_like = $conn->prepare($query_delete_like);
        $stmt_delete_like->bindParam(':like_id', $existing_like['like_id']);
        $stmt_delete_like->execute();

        // Return a success response and the new total likes
        $total_likes = countLikes($liked_item_id, $liked_item_type);
        http_response_code(200); // OK
        echo json_encode(array('success' => true,'message' => 'Like removed successfully','total_likes' => $total_likes));
        exit();
    } else {
        // User has not liked the post yet, insert the like
        $query_insert_like = "INSERT INTO likes (user_id, ";
        if ($liked_item_type == 'story') {
            $query_insert_like .= "story_id, liked_item_type) VALUES (:user_id, :liked_item_id, 'story')";
        } elseif ($liked_item_type == 'comment') {
            $query_insert_like .= "comment_id, liked_item_type) VALUES (:user_id, :liked_item_id, 'comment')";
        }
        $stmt_insert_like = $conn->prepare($query_insert_like);
        $stmt_insert_like->bindParam(':user_id', $user_id);
        $stmt_insert_like->bindParam(':liked_item_id', $liked_item_id);
        $stmt_insert_like->execute();

        // Return a success response and the new total likes
        $total_likes = countLikes($liked_item_id, $liked_item_type);

        // Return a success response and the new total likes
        $total_likes = countLikes($liked_item_id, $liked_item_type);


        http_response_code(201); // Created
        echo json_encode(array('success' => true,'message' => 'Like submitted successfully','total_likes' => $total_likes));
        exit();

    }
}

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Assuming you receive the liked item ID and type from the GET request
    $liked_item_id = $_GET['id'];
    $liked_item_type = $_GET['type'];

    // Count the total number of likes for the item
    $total_likes = countLikes($liked_item_id, $liked_item_type);

    // Return the total number of likes
    http_response_code(200); // OK
    echo json_encode(array('total_likes' => $total_likes['total_likes']));
    exit();
}

function countLikes($liked_item_id, $liked_item_type) {
    global $conn;
    $query_count_likes = "SELECT COUNT(like_id) AS total_likes FROM likes WHERE ";
    if ($liked_item_type == 'story') {
        $query_count_likes .= "story_id = :liked_item_id AND liked_item_type = 'story'";
    } elseif ($liked_item_type == 'comment') {
        $query_count_likes .= "comment_id = :liked_item_id AND liked_item_type = 'comment'";
    }
    $stmt_count_likes = $conn->prepare($query_count_likes);
    $stmt_count_likes->bindParam(':liked_item_id', $liked_item_id);
    $stmt_count_likes->execute();
    $total_likes = $stmt_count_likes->fetch(PDO::FETCH_ASSOC);
    return $total_likes['total_likes'];
}
?>
