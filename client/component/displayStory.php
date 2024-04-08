<!-- render_story.php -->
<?php
include 'parent_comment.php';
include 'button/deleteButton.php';
include 'button/replyButton.php';
include 'button/likeButton.php';
function renderStory($story, $comments) {

    ?>

    <div class="story">
        <div class="story-content">
            <span hidden="story id :"><?= htmlspecialchars($story->id) ?></span>
            <span class="author"><?= htmlspecialchars($story->author) ?></span>
            <p class="content"><?= htmlspecialchars($story->content) ?></p>
            <span class="date"><?= htmlspecialchars($story->date) ?></span>
            <?php
            renderLikeButton($story->id, true);
            renderReplyForm($story->id, 0);
            renderDeleteButton($story->id, true);
            renderParentComments($comments);
            ?>

        </div>
    </div>



    <style>
        .story {
            padding: 10px;
            border: 2px solid;
            border-radius: 10px;
            background-color: #b6bbc4;
            color: #000;
            margin-bottom: 5px;
        }

        .delete-button {
            background-color: #000000;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        .delete-button:hover {
            background-color: #d80000;
        }
    </style>

    <?php

}

?>
