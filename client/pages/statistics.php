<?php
/*
 * This is the statistics page. It displays the number of stories and comments.
 * It also displays the number of likes and comments per story.
 * It also displays the number of comments per user.
 * It also displays the number of likes per user.
 * It also displays the number of stories per user.
 * credits: https://clundi.fr, github-copilot & chatGPT
 */
include "../component/navBar.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/statistics.css">
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
    fetch("../../api/statistics.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("story-count").innerText = data.storyCount;
            document.getElementById("comment-count").innerText = data.commentCount;
            document.getElementById("like-count").innerText = data.likeCount;
            document.getElementById("comment-count-per-story").innerText = data.commentCountPerStory;
        });
</script>
