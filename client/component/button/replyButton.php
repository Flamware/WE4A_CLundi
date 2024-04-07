<?php
function renderReplyForm($storyId, $author, $parent_comment_id_value)
{
    // Generate a unique ID based on storyId and parent_comment_id_value
    $containerId = "textAreaContainer_" . $storyId . "_" . $parent_comment_id_value;
    ?>
    <div class="reply-form">
        <button class="show-button" data-container-id="<?php echo $containerId; ?>">
            Répondre
        </button>
        <div id="<?php echo $containerId; ?>" class="text-area-container" style="display: none;">
            <form id="commentForm_<?php echo $containerId; ?>" class="comment-form" method="post"
                  action="http://localhost/submit-comment.php">
                <textarea name="content" placeholder="Tapez votre réponse" class="reply-textarea"></textarea>
                <!-- Use props passed from the upper component -->
                <input type="hidden" name="story_id" value="<?php echo $storyId; ?>">
                <input type="hidden" name="parent_comment_id" value="<?php echo $parent_comment_id_value; ?>">
                <!-- Use the converted value -->
                <div class="button-container">
                    <button type="button" class="hide-button" data-container-id="<?php echo $containerId; ?>">
                        Masquer la réponse
                    </button>
                    <button type="button" class="reply-button" onclick="submitReply(this)">
                        Répondre
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>

<script>
    // Function to retrieve stored cookie from localStorage
    function getStoredCookie() {
        return localStorage.getItem('authCookie');
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Add event listeners for show/hide buttons
        var showButtons = document.querySelectorAll('.show-button');
        showButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var containerId = this.getAttribute('data-container-id');
                toggleTextArea(containerId);
            });
        });

        var hideButtons = document.querySelectorAll('.hide-button');
        hideButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var containerId = this.getAttribute('data-container-id');
                hideTextArea(containerId);
            });
        });
    });

    function toggleTextArea(containerId) {
        var container = document.getElementById(containerId);
        container.style.display = container.style.display === "none" ? "block" : "none";
    }
    function submitReply(button) {
        var form = button.closest('.comment-form');
        var formData = new FormData(form);
        fetch('http://localhost/submit-comment.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => handleResponse(data))
            .catch(error => console.error('Error:', error));
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
        padding: 8px 12px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    .text-area-container {
        margin-top: 10px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .reply-textarea {
        width: calc(100% - 22px);
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
        padding: 8px 12px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
        margin-left: 5px;
    }
</style>
