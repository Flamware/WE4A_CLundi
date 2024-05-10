<?php
/*
 * This is the statistics page. It displays the number of stories and comments.
 * It also displays the number of likes and comments per story.
 * It also displays the number of comments per user.
 * It also displays the number of likes per user.
 * It also displays the number of stories per user.
 * credits: https://clundi.fr, github-copilot & chatGPT
 */
session_start();
// prevent session writing
session_write_close();
// prevent access to unauthenticated users
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require '../../conf.php';
include "../component/bar/navBar.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/bar/navbar.css">
    <link rel="stylesheet" href="../css/pages/statistics.css">

</head>
<body>
<?php include '../component/header.php'; ?>
<?php displayNavBar(); ?>

<div class="main-container">
<div class="container">
    <div class="statistics">
        <h1>Statistiques</h1>
        <div class="statistics-container">
            <div class="statistics-item">
                <h2>Nombre de posts</h2>
                <p id="story-count">0</p>
            </div>
            <div class="statistics-item">
                <h2>Nombre de commentaires</h2>
                <p id="comment-count">0</p>
            </div>
            <div class="statistics-item">
                <h2>Nombre de likes</h2>
                <p id="like-count">0</p>
            </div>
        </div>
    </div>
    <div class="statistics">
        <h1>Statistiques par post</h1>
        <div class="statistics-container">
            <div class="statistics-item">
                <h2>Nombre de commentaires reçus</h2>
                <p id="comment-count-per-story">0</p>
            </div>
        </div>
    </div>
    <!-- Add more sections for other statistics as needed -->
</div>
</div>
<?php include '../component/footer.php'; ?>
</body>
</html>
<script>
    console.log("Fetching statistics...");
    // On page load, asynchronously fetch the statistics
    fetch(apiPath + '/statistics.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Update the statistics on the page
            document.getElementById('story-count').textContent = data.storyCount;
            document.getElementById('comment-count').textContent = data.commentCount;
            document.getElementById('like-count').textContent = data.likeCount;
            document.getElementById('comment-count-per-story').textContent = data.commentCountPerStory;
        })
        .catch(error => {
            console.error('Error:', error);
        });

</script>
