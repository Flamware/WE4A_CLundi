document.addEventListener("DOMContentLoaded", function () {
    // Add event listeners for show/hide buttons
    var showButtons = document.querySelectorAll('.show-button');
    showButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var containerId = this.getAttribute('data-container-id');
            toggleTextArea(containerId);
        });
    });

    var hideButtons = document.querySelectorAll('.hide-button');
    hideButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var containerId = this.getAttribute('data-container-id');
            hideTextArea(containerId);
        });
    });

    var replyForms = document.querySelectorAll('.reply-form form');
    replyForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission
            var formData = new FormData(form);
            var url = apiPath+'/submit/submitComment.php'; // Your API endpoint
            fetch(url, {
                method: 'POST',
                body: formData
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
    });
});

function toggleTextArea(containerId) {
    var container = document.getElementById(containerId);
    container.style.display = container.style.display === "none" ? "block" : "none";
}

function hideTextArea(containerId) {
    var container = document.getElementById(containerId);
    container.style.display = "none";
}