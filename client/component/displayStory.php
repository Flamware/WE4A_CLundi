<?php
include 'parent_comment.php';
include 'button/deleteButton.php';
include 'form/replyForm.php';
include 'button/likeButton.php'; // Corrected inclusion
require 'form/reportForm.php';
function renderStory($story, $comments) {
    ?>
    <div class="story">
        <div class="story-header">
            <!-- Author's profile picture -->
            <a href="../pages/wall.php?username=<?= urlencode($story->author) ?>">
                <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($story->author) ?>">
            </a>

            <!-- Author's name -->
            <a href="../pages/wall.php?username=<?= urlencode($story->author) ?>" class="author"><?= htmlspecialchars($story->author) ?></a>
        </div>

        <div class="story-content">
            <!-- Content of the story -->
            <p><?= htmlspecialchars($story->content) ?></p>

            <!-- Story image, if available -->
            <?php if (!empty($story->story_image)) : ?>
                <img src="<?= API_PATH ?>/uploads/stories/<?= htmlspecialchars($story->story_image) ?>" alt="Story Image" class="story-image">
            <?php endif; ?>

            <!-- Date of the story -->
            <span class="date"><?= htmlspecialchars($story->date) ?></span>
        </div>

        <div class="option">
            <!-- Options like reply, like, delete, etc. -->
            <?php
            renderReplyForm($story->id, 0);
            renderLikeButton($story->id, true, $story->like_count);
            if ((isset($_SESSION['username']) && $_SESSION['username'] === $story->author) || (isset($_SESSION['admin']) && $_SESSION['admin'] === true)) {
                renderDeleteButton($story->id, true);
            }
            ?>
        </div>

        <?php
        displayReportForm("story", $story->id);

        // Use story ID to create a unique ID for the comment section
        $commentSectionId = 'comment-section' . $story->id;
        ?>
        <button id="toggle-comments-<?= $story->id ?>" onclick="toggleVisibility('<?= $commentSectionId ?>')">Voir les commentaires</button>
        <?php
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
    <?php
}
?>