<?php
function renderReplyForm($storyId, $author, $parent_comment_id_value) {
    // Generate a unique ID based on storyId and parent_comment_id_value
    $containerId = "textAreaContainer_" . $storyId . "_" . $parent_comment_id_value;
    ?>
    <div class="reply-form">
        <button onclick="toggleTextArea('<?php echo $containerId; ?>')" class="show-button">
            Répondre
        </button>
        <div id="<?php echo $containerId; ?>" class="text-area-container" style="display: none;">
            <form method="post" action="http://localhost/submit-comment.php">
                <textarea name="content" placeholder="Tapez votre réponse" class="reply-textarea"></textarea>
                <!-- Use props passed from the upper component -->
                <input type="hidden" name="story_id" value="<?php echo $storyId; ?>">
                <input type="hidden" name="author" value="<?php echo $author; ?>">
                <input type="hidden" name="parent_comment_id" value="<?php echo $parent_comment_id_value; ?>"> <!-- Use the converted value -->
                <div class="button-container">
                    <button type="button" onclick="hideTextArea('<?php echo $containerId; ?>')" class="hide-button">Masquer la réponse</button>
                    <button type="submit" name="reply" class="reply-button">Répondre</button>
                </div>
            </form>
        </div>
    </div>

    <?php
}
?>
<style>
    .reply-form {
        display: flex;
        align-items: flex-end;
    }

    .show-button {
        background-color: #fc6736;
        color: #ffffff;
        margin-top: 5px;
        margin-right: 5px; /* Adjusted margin-right */
        padding: 8px 12px; /* Adjusted padding */
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    .text-area-container {
        margin-top: 10px; /* Adjusted margin-top */
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .reply-textarea {
        width: calc(100% - 22px); /* Adjusted width */
        resize: vertical;
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }

    .reply-button,
    .hide-button {
        background-color: #31304d;
        color: #fff;
        padding: 8px 12px; /* Adjusted padding */
        cursor: pointer;
        border: none;
        border-radius: 4px;
        margin-left: 5px;
    }
</style>

<script>
    function toggleTextArea(containerId) {
        var container = document.getElementById(containerId);
        if (container.style.display === "none") {
            container.style.display = "block";
        } else {
            container.style.display = "none";
        }
    }

    function hideTextArea(containerId) {
        var container = document.getElementById(containerId);
        container.style.display = "none";
    }
</script>
