<?php
// Access props passed from the upper component
$storyId = htmlspecialchars($story->id);
$author = htmlspecialchars($story->author);
$parent_comment_id = null; // Set parent_comment_id to null if no parent comment is available
$parent_comment_id_value = $parent_comment_id !== null ? $parent_comment_id : 'null';
?>

<div class="reply-form">
    <button onclick="toggleTextArea()" class="show-button">
        Répondre
    </button>
    <div id="textAreaContainer" class="text-area-container" style="display: none;">
        <form method="post" action="http://localhost/submit-comment.php">
            <textarea name="content" placeholder="Tapez votre réponse" class="reply-textarea"></textarea>
            <!-- Use props passed from the upper component -->
            <input type="hidden" name="story_id" value="<?php echo $storyId; ?>">
            <input type="hidden" name="author" value="<?php echo $author; ?>">
            <input type="hidden" name="parent_comment_id" value="<?php echo $parent_comment_id_value; ?>"> <!-- Use the converted value -->
            <div class="button-container">
                <button type="button" onclick="hideTextArea()" class="hide-button">Masquer la réponse</button>
                <button type="submit" name="reply" class="reply-button">Répondre</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTextArea() {
        var textAreaContainer = document.getElementById("textAreaContainer");
        if (textAreaContainer.style.display === "none") {
            textAreaContainer.style.display = "block";
        } else {
            textAreaContainer.style.display = "none";
        }
    }

    function hideTextArea() {
        var textAreaContainer = document.getElementById("textAreaContainer");
        textAreaContainer.style.display = "none";
    }
</script>

<style>
    .reply-form {
        display: flex;
        align-items: flex-end;
    }

    .show-button {
        background-color: #fc6736;
        color: #ffffff;
        margin-top: 5px;
        margin-right: 5px;
        padding: 10px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    .text-area-container {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .reply-textarea {
        width: 100%;
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
        padding: 10px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
        margin-left: 5px;
    }
</style>
