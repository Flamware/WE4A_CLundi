<div class="story">
    <div class="story-content">
        <span hidden="story id :"><?php echo htmlspecialchars($story->id); ?></span>
        <span class="author"><?php echo htmlspecialchars($story->author); ?></span>
        <p class="content"><?php echo htmlspecialchars($story->content); ?></p>
        <span class="date"><?php echo htmlspecialchars($story->date); ?></span>
        <?php
        // Pass necessary data as props to the reply form
        include '../component/reply_form.php';
        ?>
        <?php
        // Include comment section component
        include '../component/parent_comment.php';
        ?>
        <!-- Use a hidden iframe to submit the form asynchronously -->
        <iframe id="deleteFrame" name="deleteFrame" style="display:none;"></iframe>
        <form id="deleteForm" method="post" action="http://localhost/delete-story.php" target="deleteFrame">
            <input type="hidden" name="story_id" value="<?php echo htmlspecialchars($story->id); ?>">
            <button type="button" onclick="submitDeleteForm()" class="delete-button">Supprimer post</button>
        </form>
    </div>
</div>

<script>
    function submitDeleteForm() {
        // Submit the form when the button is clicked
        document.getElementById("deleteForm").submit();
        setTimeout(function() {
            window.location.reload();
        }, 1000); // Adjust the delay as needed (1 second in this example)
    }
</script>

<style>
    .story {
        padding: 10px;
        border: 2px solid ;
        border-radius: 10px;
        background-color: #b6bbc4;
        color: #000;
        margin-bottom: 5px;
    }

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
