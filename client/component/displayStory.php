<div class="story">
    <div class="story-content">
        <span hidden="story id :"><?php echo htmlspecialchars($story->id); ?></span>
        <span class="author"><?php echo htmlspecialchars($story->author); ?></span>
        <p class="content"><?php echo htmlspecialchars($story->content); ?></p>
        <span class="date"><?php echo htmlspecialchars($story->date); ?></span>
        <?php
        // Pass necessary data as props to the reply form
        include '../component/button/replyButton.php';
        include '../component/button/deleteButton.php';
        include '../component/parent_comment.php';
        renderReplyForm($story->id, $story->author, 0);
        renderDeleteButton($story->id, true);
        ?>
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
