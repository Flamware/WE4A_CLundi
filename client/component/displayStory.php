<?php
include 'parent_comment.php';
include 'button/deleteButton.php';
include 'form/replyForm.php';
include 'button/likeButton.php';
include 'button/renderCommentButton.php'; // Corrected inclusion
require 'form/reportForm.php';
function renderStory($story, $comments) {
    ?>
    <div class="story">
        <div class="story-content">
            <a href="../pages/wall.php?username=<?= urlencode($story->author) ?>">
                <img src="http://localhost/api/uploads/profile_picture/default_profile_picture.jpg" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($story->author) ?>">
            </a>
            <span hidden="story id :"><?= htmlspecialchars($story->id) ?></span>
            <a href="../pages/wall.php?username=<?= urlencode($story->author) ?>" class="author"><?= htmlspecialchars($story->author) ?></a>
            <p class="content"><?= htmlspecialchars($story->content) ?></p>
            <span class="date"><?= htmlspecialchars($story->date) ?></span>
        </div>

        <span class="option">
            <?php
            renderReplyForm($story->id, 0);
            renderLikeButton($story->id, true, $story->like_count);
            renderDeleteButton($story->id, true);
            ?>
        </span>
        <!-- The report form section with unique ID -->
        <?php displayReportForm('story', $story->id); ?>

        <?php
        // Use story ID to create a unique ID for the comment section
        $commentSectionId = 'comment-section' . $story->id;
        // Pass the comment section ID and comments data to render the comment button
        renderCommentButton($commentSectionId, "Voir les commentaires");
        ?>
        <?php if (count($comments) > 0) { ?>
        <!-- The comment section with unique ID -->
        <div id="<?= $commentSectionId ?>" style="display: none;">
            <?php
            // Render parent comments inside this section
            renderParentComments($comments);
            ?>
        </div>
        <?php } ?>

    </div>


    <script>
        function toggleVisibility(targetId) {
            var target = document.getElementById(targetId);
            if (target.style.display === "none") {
                target.style.display = "block";
            } else {
                target.style.display = "none";
            }
        }

    </script>

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
        .author {
            font-weight: bold;
        }
        .delete-button:hover {
            background-color: #d80000;
        }


        .profile-picture {
            float: left;
            margin-right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%; /* Make it a circle */
            object-fit: cover; /* Maintain aspect ratio */
            object-position: center; /* Center the image */
            display: block;
        }

    </style>

    <?php
}
?>
