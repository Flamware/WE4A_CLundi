<?php
function displayStoryForm() {
    ?>
    <section id="submit-story">
        <label for="story">Votre post :</label>
        <textarea id="story" rows="4" required></textarea>
        
        <label for="story-image">Ajouter une image :</label>
        <input type="file" id="story-image" accept="image/*"> <!-- File input for images -->

        <!-- Image preview section -->
        <div id="image-preview-container">
            <img id="image-preview" src="" alt="Image Preview" style="display: none;" /> <!-- Initially hidden -->
        </div>
        
        <button id="submit-story-btn">Partager</button>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener to the file input to handle image previews
        document.getElementById('story-image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            
            if (file) {
                // Create a URL for the selected file and set it as the image source
                const fileURL = URL.createObjectURL(file);
                preview.src = fileURL; // Set the preview image source
                preview.style.display = 'block'; // Make the preview visible
            } else {
                preview.src = ''; // Clear the preview
                preview.style.display = 'none'; // Hide the preview
            }
        });

        document.getElementById('submit-story-btn').addEventListener('click', function () {
            // Retrieve story content and image
            var storyContent = document.getElementById('story').value;
            var storyImage = document.getElementById('story-image').files[0]; // Get the first selected file

            // Create FormData for the POST request
            var formData = new FormData();
            formData.append('story', storyContent);
            formData.append('story_image', storyImage); // Add the image to the form data

            // Send POST request to the server
            fetch('<?php echo API_PATH; ?>/submit/submitStory.php', {
                method: 'POST',
                body: formData // Use FormData for file uploads
            })
            .then(response => response.json()) // Parse the JSON response
            .then(data => {
                if (data.success) {
                    showError(data.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    console.log('Condition is false:', data.success);
                    showError(data.message);
                }

            })
            .catch(error => {
                console.error('Error:', error); // Handle fetch errors
                showError('An error occurred while submitting the story. Please try again.');
            });
        });
    });
    </script>
    <?php
}
