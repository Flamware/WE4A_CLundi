<?php
function displayMessageForm() {
    ?>
    <form id="message-form" action="http://localhost/api/submit/submitMessage.php" method="post">
        <h3>Send a message</h3>
        <input type="text" class="fetched-user" placeholder="Search user..." />
        <div class="suggestions-container"></div>
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
        background-color: #aab8c2; /* Clean background */
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
