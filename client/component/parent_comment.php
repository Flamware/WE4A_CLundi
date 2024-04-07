<!-- render_parent_comment.php -->
<?php

function getParentComment($comments) {
    if (!is_array($comments)) {
        return [];
    }
    return array_filter($comments, function($comment) {
        return $comment->parent_comment_id == 0;
    });
}
function getRepliesByCommentId($comments, $parent_comment_id) {
    if (!is_array($comments)) {
        return [];
    }
    return array_filter($comments, function($comment) use ($parent_comment_id) {
        return $comment->parent_comment_id == $parent_comment_id;
    });
}

function renderParentComments($comments) {
    echo '<div class="comment-section">';
    if (!empty($comments)) {
        foreach (getParentComment($comments) as $comment):
            ?>
            <div class="comment">
                <span class="comment-author"><?php echo htmlspecialchars($comment->author); ?></span>
                <p class="comment-content"><?php echo htmlspecialchars($comment->content); ?></p>
                <span class="comment-date"><?php echo htmlspecialchars($comment->created_at); ?></span>
                <?php
                renderReplyForm($comment->story_id, $comment->author, $comment->id);
                renderDeleteButton($comment->id, false);
                require_once 'render_replies.php';
                renderComments(getRepliesByCommentId($comments, $comment->id), $comments);
                ?>
            </div>
        <?php
        endforeach;
    } else {
        echo "<p>No comments available.</p>";
    }

    echo '</div>';
}
?>

<style >
    .comment {
        background-color: #f0ece5;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .comment > div {
        margin-top: 5px;
        margin-left: 20px;
    }

    .comment-author {
        font-weight: bold;
    }
    .button-container{
        display: flex;
        align-items: center;
        margin-top: 5px;
        justify-content: space-between;
    }

</style>


<style >
    .comment {
        background-color: #f0ece5;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .comment > div {
        margin-top: 5px;
        margin-left: 20px;
    }

    .comment-author {
        font-weight: bold;
    }
    .button-container{
        display: flex;
        align-items: center;
        margin-top: 5px;
        justify-content: space-between;
    }

</style>
