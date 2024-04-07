<?php
function renderDeleteButton($id, $isStory)
{
    ?>
    <form class="deleteForm" action="http://localhost/delete/<?php echo $isStory ? 'story' : 'comment'; ?>.php" method="post">
        <input type="hidden" name="<?php echo $isStory ? 'story_id' : 'comment_id'; ?>" value="<?php echo $id; ?>">
        <button type="button" class="delete-button" onclick="submitDeleteForm(this)">Delete</button>
    </form>
    <?php
}
?>
<script>
    function submitDeleteForm(button) {
        var form = button.closest('.deleteForm'); // Find the closest form
        form.submit(); // Submit the form
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    }
</script>

<style>
    /* Style the button */
    .delete-button {
        background-color: #000000; /* Red background */
        color: #ffffff; /* White text color */
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
    }

    .delete-button:hover {
        background-color: #d80000; /* Darker red on hover */
    }
</style>
