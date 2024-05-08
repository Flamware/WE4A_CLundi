<?php
function displayMessageForm() {
    ?>
    <form id="message-form" action="http://localhost/api/submit/submitMessage.php" method="post">
        <h3>Send a message</h3>
        <input type="text" name="receiver" class="fetched-user" placeholder="Enter receiver's username" />
        <div class="suggestions-container"></div>
        <textarea name="message" placeholder="Your message here..."></textarea>
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
                xhr.open('POST', 'http://localhost/api/submit/submitMessage.php', true); // Define POST request

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

<style>
    #message-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px; /* Light padding for a compact design */
        border: 2px solid rgba(255, 255, 255, 0.5); /* Semi-transparent border */
        border-radius: 20px;
        background-color: rgba(170, 184, 194, 0.5); /* Transparent background */
        backdrop-filter: blur(10px); /* Apply a blur effect */
        height: 100%; /* Full height */
        margin: 20px 0; /* Space around the form */
        border: 2px solid black;

    }

    #message-form h3 {
        margin-bottom: 10px;
    }

    #message-form input,
    #message-form textarea {
        width: 100%; /* Ensure the input fields take up the full width */
        padding: 5px; /* Compact padding */
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px; /* Space between elements */
    }

    #message-form button {
        padding: 5px 15px; /* Slightly smaller padding */
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #message-form button:hover {
        background-color: #0056b3; /* Change hover color */
    }


    .suggestion ul {
        list-style-type: none; /* Remove bullet points */
        padding: 0; /* No padding */
        margin: 0; /* No margin */
    }

    .suggestion li {
        padding: 5px; /* Compact padding */
        cursor: pointer;
    }

    .suggestion li:hover {
        background-color: #f9f9f9; /* Highlight on hover */
    }
</style>
    <?php
}
?>