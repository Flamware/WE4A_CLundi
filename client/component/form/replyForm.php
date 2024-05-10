<?php
function renderReplyForm($storyId, $parent_comment_id_value)
{
    // Generate a unique ID based on storyId and parent_comment_id_value
    $containerId = "textAreaContainer_" . $storyId . "_" . $parent_comment_id_value;
    ?>
    <div class="reply-form">
        <div id="error-message" class="error-message" style="display: none;"></div>
        <button class="show-button" data-container-id="<?php echo $containerId; ?>">
            Répondre
        </button>
        <div id="<?php echo $containerId; ?>" class="text-area-container" style="display: none;">
            <form id="replyForm_<?php echo $containerId; ?>" class="reply-form" action="<?php echo API_PATH?>/submit-comment" method="post">
                <textarea name="content" placeholder="Tapez votre réponse" class="reply-textarea"></textarea>
                <input type="hidden" name="story_id" value="<?php echo $storyId; ?>">
                <input type="hidden" name="parent_comment_id" value="<?php echo $parent_comment_id_value; ?>">
                <input type="hidden" name="action" value="reply">
                <div class="button-container">
                    <button type="button" class="hide-button" data-container-id="<?php echo $containerId; ?>">
                        Masquer
                    </button>
                    <button type="submit" class="reply-button">
                        Répondre
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>