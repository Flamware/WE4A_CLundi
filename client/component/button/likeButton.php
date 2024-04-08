<?php
/*
 * This file contains the like button component
 * a post request is sent to like-comment.php to like a comment or to like-story.php to like a story
 * The like button is displayed with a click event listener that sends a post request to the server
 */
?>
<?php
function renderLikeButton($id, $isStory)
{
    $likeType = $isStory ? 'story' : 'comment';
    ?>
    <div class="like-button-container">
        <button class="like-button" data-id="<?php echo $id; ?>" data-type="<?php echo $likeType; ?>">
            Like
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
                var url = 'http://localhost/api/like-' + type + '.php';
                var formData = new FormData();
                formData.append('id', id);
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        showError(data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError(error.message);
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
</style>

