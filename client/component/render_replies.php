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
                renderReplyForm($reply->story_id, $reply->author, $reply->id);
                renderDeleteButton($reply->id, false);
                renderComments(getRepliesByCommentId($comments, $reply->id), $comments);
                ?>
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
