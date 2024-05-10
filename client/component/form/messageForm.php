<?php
function displayMessageForm() {
    ?>
    <form id="message-form" action="<?php echo API_PATH?>/submit/submitMessage.php" method="post">
        <h3>Send a message</h3>
        <input type="text" name="receiver" class="fetched-user" placeholder="Receiver..." />
        <div class="suggestions-container"></div>
        <textarea name="message" placeholder="Your message"></textarea>
        <button type="submit">Send</button>
    </form>


    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const messageForm = document.getElementById('message-form');

            if (!messageForm) {
                console.error('Form not found');
                return;
            }

            messageForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(messageForm); // Create FormData from the form itself

                const xhr = new XMLHttpRequest();
                xhr.open('POST', apiPath + '/submit/submitMessage.php', true); // Define POST request

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        showError("Message sent successfully");
                        messageForm.reset(); // Reset the form
                        //if location is message page, reload the page
                        if (window.location.href.includes('message')) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    } else {
                        // Decode the JSON response and display the error message
                        const response = JSON.parse(xhr.responseText);
                        showError(response.message); // Display error message
                    }
                };

                xhr.onerror = function() {
                    console.error('Network error occurred during the request');
                    showError('Network error. Please try again later.');
                };

                xhr.send(formData); // Send the form data
            });
        });

    </script>


    <?php
}
?>