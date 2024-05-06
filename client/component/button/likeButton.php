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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var likeButtons = document.querySelectorAll('.like-button');
        likeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var type = this.getAttribute('data-type');
                var formData = new FormData();
                formData.append('id', id);
                formData.append('type', type);
                fetch('../../../api/submit/submitLike.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json()) // Parse response as JSON
                    .then(data => {
                        // Check if the submission was successful
                        if(data.success){
                            // Show success message
                            showError(data.message);
                            // Update the number of likes displayed
                            document.getElementById('num-likes-' + id).textContent = data.total_likes;

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
</script>

<style>
    .like-button-container {
        margin-top: 5px;
    }

    .like-button {
        background-color: #0066ff;
        color: #ffffff;
        padding: 8px 12px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }
    .option {
        display: flex;
        justify-content: space-between;
    }
</style>

