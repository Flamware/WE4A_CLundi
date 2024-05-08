document.addEventListener('DOMContentLoaded', function () {
    var likeButtons = document.querySelectorAll('.like-button');
    likeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var type = this.getAttribute('data-type');
            var formData = new FormData();
            formData.append('id', id);
            formData.append('type', type);
            console.log('Button clicked'); // Add this line to check if the event listener is triggered
            fetch(apiPath+'/submit/submitLike.php', {
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