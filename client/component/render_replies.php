<?php
function renderComments( $replies = [],$comments) {
    if (!empty($replies)) {
        foreach ($replies as $reply) {
            ?>
            <div class="comment">
                <span class="comment-author"><?php echo htmlspecialchars($reply->author); ?></span>
                <p class="comment-content"><?php echo htmlspecialchars($reply->content); ?></p>
                <span class="comment-date"><?php echo htmlspecialchars($reply->created_at); ?></span>
                <?php
                renderComments(getRepliesByCommentId($comments, $reply->id), $comments);
                ?>
            </div>
            <?php
        }
    }
}
?>
