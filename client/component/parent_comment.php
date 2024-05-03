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
                <a href="../pages/wall.php?username=<?= urlencode($comment->author) ?>">
                    <img src="http://localhost/api/profile_picture/default_profile_picture.jpg" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($comment->author) ?>">
                </a>
                <span hidden="comment id :"><?php echo htmlspecialchars($comment->id); ?></span>
                <span class="comment-author">
                    <a href="../pages/wall.php?username=<?= urlencode($comment->author) ?>"><?php echo htmlspecialchars($comment->author); ?></a>
                </span>                <p class="comment-content"><?php echo htmlspecialchars($comment->content); ?></p>
                <span class="comment-date"><?php echo htmlspecialchars($comment->created_at); ?></span>
                <span class="option">
                    <?php
                    // Render toggle button for replies
                    renderReplyForm($comment->story_id, $comment->id);
                    renderLikeButton($comment->id, false, $comment->like_count);
                    renderDeleteButton($comment->id, false);
                    ?>
                </span>
                <?php
                displayReportForm('comment', $comment->id);
                $toggleButtonId = 'replies-' . $comment->id;
                renderCommentButton($toggleButtonId, 'Voir les réponses');
                ?>
                <div class="replies" id="<?php echo $toggleButtonId; ?>" onload="toggleVisibility(this)" style="display: none;">
                    <?php
                    require_once 'render_replies.php';
                    renderComments(getRepliesByCommentId($comments, $comment->id), $comments);
                    ?>
                </div>
            </div>
        <?php
        endforeach;
    } else {
        echo "<p>No comments available.</p>";
    }

    echo '</div>';
}
?>

<style>
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

    /* Style for toggle button */
    .toggle-button-container {
        margin-top: 5px;
    }

    .toggle-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .toggle-button:hover {
        background-color: #0056b3;
    }
</style>
