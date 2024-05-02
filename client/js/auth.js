    document.addEventListener('DOMContentLoaded', function() {
    // Send an asynchronous request to check authentication status
    fetch('../../api/auth.php', {
        method: 'GET',
        credentials: 'include'
    })
        .then(response => {
            if (response.ok) {
            } else {
                // User is not authenticated, pr    event loading resources
                showError('You are not authenticated. Please log in to continue.');
                // Redirect to login page after a delay
                setTimeout(function () {
                    window.location.href = 'login.php';
                }, 1000);
            }
        })
        .catch(error => {
            // Handle fetch errors
            console.error('Error checking authentication:', error);
            showError('An error occurred while checking authentication. Please try again.');
        });

});

