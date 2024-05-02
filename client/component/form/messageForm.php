<?php
function displayMessageForm() {
    ?>
    <form id="message-form" action="http://localhost/api/submit/submitMessage.php" method="post">
        <h3>Send a message</h3>
        <input type="text" name="receiver" id="receiver" placeholder="Recipient's Name">
        <!-- Suggestions container for autocomplete suggestions -->
        <div id="suggestions-container"></div>
        <textarea name="message" placeholder="Your message here..."></textarea>
        <button type="submit">Send</button>
    </form>
    <?php
}
?>


<style>
    #message-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start; /* Align items to the left */
        padding: 10px; /* Light padding for a compact design */
        border: 1px solid #ccc; /* Light border */
        border-radius: 5px;
        background-color: white; /* Clean background */
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
    #suggestions-container {
        position: relative; /* Relative to the form */
        width: 100%; /* Ensure it spans the full width */
    }

    .suggestion {
        background-color: white; /* Consistent background */
        border: 1px solid #ccc; /* Light border */
        border-radius: 5px; /* Smooth corners */
        padding: 5px; /* Compact padding */
        cursor: pointer; /* Change cursor on hover */
    }

    .suggestion-container {
        max-height: 150px; /* Limit height to control expansion */
        overflow-y: auto; /* Enable vertical scrolling if there are more than 5 suggestions */
        position: absolute; /* Absolute position to stay under the input field */
        z-index: 10; /* Ensure it's visible above other elements */
        width: 100%; /* Full width */
        background-color: white; /* Background color to align with suggestions */
        border: 1px solid #ccc; /* Border for visual definition */
        border-radius: 5px; /* Smooth corners */
    }
</style>
