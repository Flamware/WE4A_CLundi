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
                    // Remove the deleted element from the DOM
                    form.closest('.story').remove();
                } else {
                    // Show error message
                    showError(response.message);
                }
            } else {
                // Request failed, handle error response
                console.error('Error:', xhr.status);
            }
        }
    };

    xhr.open('POST', url); // Set request method and URL
    xhr.send(formData); // Send the request with form data
}