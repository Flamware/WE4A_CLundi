<?php

function displayStoryForm(){
    ?>
    <section id="submit-story">
    <label for="story">Votre post :</label>
    <textarea id="story" rows="4" required></textarea>
    <button id="submit-story-btn">Partager</button>
</section>

<script>
    // Add event listener for submit button
    document.getElementById('submit-story-btn').addEventListener('click', function () {
        // Retrieve story content
        var storyContent = document.getElementById('story').value;
        console.log(storyContent);

        // Send POST request to the server
        fetch('http://localhost/api/submit/submitStory.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'story=' + encodeURIComponent(storyContent) + '&action=submit_story'
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
</script>

    <style>
#submit-story {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        border: 2px solid;
        border-radius: 10px;
        background-color: #b6bbc4;
    }

    #submit-story label {
        margin-bottom: 10px;
    }

    #submit-story textarea {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    #submit-story button {
        padding: 5px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #submit-story button:hover {
        background-color: #0056b3;
    }
</style>
    <?php
}
?>