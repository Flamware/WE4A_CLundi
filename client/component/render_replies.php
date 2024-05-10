<?php
function renderComments( $replies = [],$comments) {
    if (!empty($replies)) {
        foreach ($replies as $reply) {
            ?>
            <div class="comment">
                <a href="../pages/wall.php?username=<?= urlencode($reply->author) ?>">
                    <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($reply->author) ?>">
                </a>
                <a href="../pages/wall.php?username=<?= urlencode($reply->author) ?>" class="comment-author">
                    <?php echo htmlspecialchars($reply->author); ?>
                </a>
                <p class="comment-content"><?php echo htmlspecialchars($reply->content); ?></p>
                <?php if (!empty($reply->comment_image)) : ?>
                    <img src="<?= API_PATH ?>/uploads/comments/<?= htmlspecialchars($reply->comment_image) ?>" alt="Comment Image" class="comment-image">
                <?php endif; ?>
                <span class="comment-date"><?php echo htmlspecialchars($reply->created_at); ?></span>
                <span class="option">
        <?php
        renderReplyForm($reply->story_id, $reply->id);
        renderLikeButton($reply->id, false, $reply->like_count);
        if ((isset($_SESSION['username']) && $_SESSION['username'] === $reply->author) || (isset($_SESSION['admin']) && $_SESSION['admin'] === true)) {
        renderDeleteButton($reply->id, false);
        }
        ?>
    </span>
                <?php
                $toggleButtonId = 'replies-' . $reply->id;
                ?>
                <button id="toggle-comments-<?= $reply->id ?>" onclick="toggleVisibility('<?= $toggleButtonId ?>')">Voir les commentaires</button>
                <div class="replies" id="<?php echo $toggleButtonId; ?>" style="display: none;">
                    <?php
                    renderComments(getRepliesByCommentId($comments, $reply->id), $comments);
                    ?>
                </div>
            </div>

            <?php
        }
    }
    // no replies
    else {
        echo "<p>No comments available.</p>";
    }
}

?>