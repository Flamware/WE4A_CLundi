<?php
/*
 * This file is used to display all the private messages between the user and other users.
 * It fetches the messages from the database and displays them in a user-friendly format.
 * source: clundi.fr
 */
include '../component/dm_thread.php'; // Include the direct message thread component
include '../component/navbar.php'; // Include the navbar component
include '../component/form/messageForm.php';

//get request to the server
$url = 'http://localhost/api/load/loadDms.php';
$data = array('action' => 'loadDMs');
$headers = array(
    'Content-type: application/x-www-form-urlencoded',
    'Cookie: ' . http_build_query($_COOKIE, '', ';')
);
$options = array(
    'http' => array(
        'header' => $headers,
        'method' => 'GET',
        'content' => http_build_query($data)
    )
);
// Create a stream context
$context = stream_context_create($options);
// Make the request and get the response
$result = file_get_contents($url, false, $context);
// If the request failed, show an error message
if ($result === FALSE) {
    echo 'Impossible de charger les messages, veuillez réessayer.';
} else {
    // Decode the JSON response
    $threads = json_decode($result, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Message Thread</title>
    <script src="../js/fetchProfilePicture.js"></script>
    <script src="../js/dmSuggestion.js"></script>
    <script src="../js/error.js"></script>
    <script src="../js/navBar.js"></script>
    <link rel="stylesheet" href="../css/messages.css">
    <link rel="stylesheet" href="../css/error.css">
</head>
<?php include '../component/header.php'; ?> <!-- Include header view -->

<?php displayNavBar(); ?>
<body>
<div id="error-message" class="error-message"></div>
<div class="container">
    <h1>Direct Messages</h1>
    <?php if (empty($threads)) : ?>
        <p>No messages found.</p>
    <?php else: ?>
        <?php
    //show the messages
        foreach ($threads as $thread) : ?>
            <div class="thread-container">
                <button class="toggle-thread-button">
                    <img src="http://localhost/api/profile_picture/default_profile_picture.jpg" alt="Profile Picture" class="profile-picture" data-author-name="<?php echo $thread['receiver'] ?>">
                    Discussion with <?php echo $thread['receiver']; ?>
                </button>
                <div class="message-thread hidden">
                    <?php displayDMThread($thread); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php
    // Include the message form
    displayMessageForm();
    ?>
    </div>
    <?php include '../component/footer.php'; ?>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-thread-button');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                console.log('Button clicked'); // Add this line to check if the event listener is triggered
                const threadContainer = this.parentElement; // Parent container of both button and message thread
                const messageThread = threadContainer.querySelector('.message-thread'); // Find the message thread within the container
                messageThread.classList.toggle('hidden'); // Toggle the visibility of the message thread
            });
        });
    });

</script>