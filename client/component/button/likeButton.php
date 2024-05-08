<?php
/*
 * This file contains the like button component
 * a post request is sent to like-comment.php to like a comment or to submitLike.php to like a story
 * The like button is displayed with a click event listener that sends a post request to the server
 */
?>
<?php function renderLikeButton($id, $isStory, $likeCount)
{
    $likeType = $isStory ? 'story' : 'comment';
    ?>
    <div class="like-button-container">
        <button class="like-button" data-id="<?php echo $id; ?>" data-type="<?php echo $likeType; ?>">
            Like
            <span class="like-count" id="num-likes-<?php echo $id; ?>"><?php echo $likeCount; ?></span> <!-- Display the number of likes here -->
        </button>
    </div>
    <?php
}
?>