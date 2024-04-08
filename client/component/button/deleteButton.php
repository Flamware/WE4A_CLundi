<?php
function renderDeleteButton($id, $isStory)
{
    ?>
    <form class="deleteForm" action="http://localhost/api/delete/<?php echo $isStory ? 'story' : 'comment'; ?>.php" method="post">
        <input type="hidden" name="<?php echo $isStory ? 'story_id' : 'comment_id'; ?>" value="<?php echo $id; ?>">
        <button type="button" class="delete-button" onclick="submitDeleteForm(this)">Delete</button>
    </form>
    <?php
}
?>
<script>
    function submitDeleteForm(button) {
        var form = button.closest('.deleteForm'); // Find the closest form
        var formData = new FormData(form); // Create form data object
        var url = form.getAttribute('action'); // Get form action URL
        var xhr = new XMLHttpRequest(); // Create new XMLHttpRequest object

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request successful, handle success response
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Display success message or perform any action
                        alert(response.message);
                        window.location.reload(); // Reload the page
                    } else {
                        // Display error message or perform any action
                        alert(response.message);
                    }
                } else {
                    // Request failed, handle error response
                    alert('Error: ' + xhr.status);
                }
            }
        };

        xhr.open('POST', url); // Set request method and URL
        xhr.send(formData); // Send the request with form data
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
