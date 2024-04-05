<div class="comment-section">
    <?php
    if (!empty($comments)) {
        foreach (getParentComment($comments) as $comment):
            ?>
            <div class="comment">
                <span class="comment-author"><?php echo htmlspecialchars($comment->author); ?></span>
                <p class="comment-content"><?php echo htmlspecialchars($comment->content); ?></p>
                <span class="comment-date"><?php echo htmlspecialchars($comment->created_at); ?></span>
                <?php
                include 'child_comment.php';
                renderComments(getRepliesByCommentId($comments, $comment->id), $comments);
                ?>
            </div>
        <?php
        endforeach;
    } else {
        echo "<p>No comments available.</p>";
    }

    function getRepliesByCommentId($comments, $parent_comment_id) {

        return array_filter($comments, function($comment) use ($parent_comment_id) {
            return $comment->parent_comment_id == $parent_comment_id;
        });
    }

    function getParentComment($comments) {
        if (!is_array($comments)) {
            return [];
        }
        return array_filter($comments, function($comment) {
            return $comment->parent_comment_id == 0;
        });

    }

    ?>
</div>

<style>
    .comment-section {
        margin-top: 10px;
    }

    .comment {
        padding: 10px;
        border: 1px solid #000;
        border-radius: 5px;
        background-color: #f0f0f0;
        margin-bottom: 5px;
    }

    .comment-author {
        font-weight: bold;
    }

    .comment-content {
        margin-top: 5px;
    }
</style>
