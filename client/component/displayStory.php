<?php
include 'parent_comment.php';
include 'button/deleteButton.php';
include 'form/replyForm.php';
include 'button/likeButton.php'; // Corrected inclusion
include 'button/renderCommentButton.php';
require 'form/reportForm.php';
require '../../conf.php'; // Include API_PATH
function renderStory($story, $comments) {
    ?>
    <div class="story">
        <div class="story-content">
            <a href="../pages/wall.php?username=<?= urlencode($story->author) ?>">
                <img src="http://localhost/api/uploads/profile_picture/default_profile_picture.jpg" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($story->author) ?>">
            </a>
            <a href="../pages/wall.php?username=<?= urlencode($story->author) ?>" class="author"><?= htmlspecialchars($story->author) ?></a>
            <p class="content"><?= htmlspecialchars($story->content) ?></p>

            <!-- Display the story image if it exists -->
            <?php if (!empty($story->story_image)) : ?>
                <img src="<?= API_PATH ?>/uploads/stories/<?= htmlspecialchars($story->story_image) ?>" alt="Story Image" class="story-image">
            <?php endif; ?>
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
        renderCommentButton($commentSectionId, "Voir les commentaires");

        if (!empty($comments)) { ?>
            <div id="<?= $commentSectionId ?>" style="display: none;">
                <?php
                // Render parent comments
                renderParentComments($comments);
                ?>
            </div>
        <?php } else { ?>
            <p>No comments to display.</p>
        <?php } ?>

    </div>

    <style>
        .story {
            padding: 10px;
            border: 2px solid;
            border-radius: 10px;
            background-color: #b6bbc4;
            color: #000;
            margin-bottom: 10px;
        }

        .story-content {
            margin-bottom: 10px; /* Space between content and other sections */
        }

        .story-image {
            max-width: 100%; /* Prevent overflow beyond the container */
            max-height: 400px; /* Limit the height of the image */
            object-fit: cover; /* Maintain aspect ratio and crop excess */
            border-radius: 10px; /* Match the story border-radius */
            margin-top: 10px; /* Space between the image and content */
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
            background-color: #d80000; /* Change color on hover */
        }

        .author {
            font-weight: bold; /* Emphasize author name */
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%; /* Circular profile picture */
            object-fit: cover; /* Maintain aspect ratio */
            object-position: center;
        }

        .date {
            font-style: italic; /* Italicize date */
        }

        .option {
            margin-top: 10px; /* Space between content and options */
        }
    </style>

    <?php
}
