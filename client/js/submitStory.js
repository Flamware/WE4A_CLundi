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
