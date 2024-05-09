<!-- render_parent_comment.php -->
<?php
function getParentComment($comments) {
    if (!is_array($comments)) {
        return []; // Return an empty array if the input is invalid
    }

    // Check for comments where parent_comment_id is null or empty
    return array_filter($comments, function ($comment) {
        return empty($comment->parent_comment_id); // Check if the parent_comment_id is empty
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
                    <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($comment->author) ?>">
                </a>
                <span hidden="comment id :"><?php echo htmlspecialchars($comment->id); ?></span>
                <span class="comment-author">
                    <a href="../pages/wall.php?username=<?= urlencode($comment->author) ?>"><?php echo htmlspecialchars($comment->author); ?></a>
                </span>                <p class="comment-content"><?php echo htmlspecialchars($comment->content); ?></p>
                <?php if (!empty($comment->comment_image)) : ?>
                    <img src="<?= API_PATH ?>/uploads/comments/<?= htmlspecialchars($comment->comment_image) ?>" alt="Comment Image" class="comment-image">
                <?php endif; ?>
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
                ?>
                <button id="toggle-comments-<?= $comment->id ?>" onclick="toggleVisibility('<?= $toggleButtonId ?>')">Voir les commentaires</button>
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