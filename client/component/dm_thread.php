<?php

function displayDMThread($thread){
    $messageAuthor = $thread['sender'];
    $messageRecipient = $thread['receiver'];
    ?>
    <script src="../js/fetchProfilePicture.js"></script>
    <link rel="stylesheet" href="../css/dm_thread.css">
    <div class="messages">
        <?php foreach ($thread['messages'] as $message) :?>
            <div class="message <?php echo $message['is_sender'] ? 'sent' : 'received'; ?>">
                <div class="message-content">
                    <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?php echo $message['is_sender'] ? $messageAuthor : $messageRecipient; ?>">
                    <div class="message-text">
                        <p><?php echo $message['message_text']; ?></p>
                        <span class="timestamp"><?php echo $message['sent_at']; ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <form class="reply-form" action="http://localhost/api/submit/submitMessage.php" method="post" onsubmit="postMessage(event, '<?php echo $messageRecipient; ?>', '<?php echo $messageAuthor; ?>')">
            <input type="hidden" name="receiver" value="<?php echo $messageRecipient; ?>">
            <textarea name="message" placeholder="Reply to <?php echo $messageRecipient; ?>"></textarea>
            <button type="submit">Reply</button>
        </form>
    </div>

<script>
    //async function postMessage
    async function postMessage(event, recipient, author) {
        event.preventDefault(); // Prevent default form submission
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action; // Get the form action URL
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            // put the success message in the error message div
            showError(result.message);
            // Clear the form
            form.reset();
            //add dynamic message
            const messages = form.parentElement;
            const message = document.createElement('div');
            const profilePicture = localStorage.getItem('profile_picture_' + author);
            message.classList.add('message', 'sent');
            message.innerHTML = `
                <div class="message-content">
                    <img src="${profilePicture}" alt="Profile Picture" class="profile-picture" data-author-name="${author}">
                    <div class="message-text">
                        <p>${formData.get('message')}</p>
                        <span class="timestamp">${new Date().toLocaleString()}</span>
                    </div>
                </div>
            `;
            messages.insertBefore(message, form);

        } else {
            // Display an error message
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = result.message;
            errorMessage.style.display = 'block';
        }
    }
</script>

<style>
    .messages {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .message {
        display: flex;
        gap: 1rem;
    }

    .message-content {
        display: flex;
        gap: 1rem;
    }

    .message-text {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .timestamp {
        font-size: 0.8rem;
        color: #777;
    }

    .reply-form {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .reply-form textarea {
        height: 100px;
    }

    .reply-form button {
        align-self: flex-end;
    }
</style>
<?php
}