<?php
function renderComments( $replies = [],$comments) {
    if (!empty($replies)) {
        foreach ($replies as $reply) {
            ?>
            <div class="comment">
                <a href="../pages/wall.php?username=<?= urlencode($reply->author) ?>">
                    <img src="http://localhost/api/profile_picture/default_profile_picture.jpg" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($reply->author) ?>">
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
        renderDeleteButton($reply->id, false);
        ?>
    </span>
                <?php
                $toggleButtonId = 'replies-' . $reply->id;
                renderCommentButton($toggleButtonId, 'Voir les réponses');
                ?>
                <div class="replies" id="<?php echo $toggleButtonId; ?>" style="display: none;">
                    <?php
                    renderComments(getRepliesByCommentId($comments, $reply->id), $comments);
                    ?>
                </div>
            </div>

            <?php
        }
    }
}

?>
<style>

    .comment {
        background-color: #f0ece5;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        margin-bottom: 10px;
    }

    .button-container {
        display: flex;
        align-items: center;
        margin-top: 5px;
        justify-content: space-between;
    }

    .comment > div {
        margin-left: 10px;
        margin-top: 5px;
    }

    .comment-author {
        font-weight: bold;
    }

</style>
