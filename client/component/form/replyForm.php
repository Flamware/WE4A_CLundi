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
            <form id="replyForm_<?php echo $containerId; ?>" class="reply-form" action="http://localhost/api/submit-comment" method="post">
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

<script>
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

        var replyForms = document.querySelectorAll('.reply-form form');
        replyForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission
                var formData = new FormData(form);
                var url = '<?php echo API_PATH?>/submit/submitComment.php'; // Your API endpoint
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json()) // Parse response as JSON
                    .then(data => {
                        // Check if the submission was successful
                        if(data.success){
                            // Show success message
                            showError(data.message);
                            // Reload the page after a delay (optional)
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Show error message if submission failed
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        // Handle fetch errors
                        console.error('Error:', error);
                        // Show error message
                        showError('An error occurred while submitting the story. Please try again.');
                    });
            });
        });
    });

    function toggleTextArea(containerId) {
        var container = document.getElementById(containerId);
        container.style.display = container.style.display === "none" ? "block" : "none";
    }

    function hideTextArea(containerId) {
        var container = document.getElementById(containerId);
        container.style.display = "none";
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
