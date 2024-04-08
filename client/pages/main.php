<?php
include '../obj/comment.php';
include '../obj/story.php';
function loadComments() {
    // get request to the server
    $url = 'http://localhost/api/load-comments.php';
    $data = array('action' => 'loadComments');
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
    //if the request failed show an error message
    if ($result === FALSE) {
        return 'Impossible de charger les commentaires, veuillez réessayer.';
    }
    return json_decode($result);
}

function getCommentsByStoryId($storyId) {
    $comments = loadComments();
    $commentsByStoryId = [];
    foreach ($comments as $comment) {
        if ($comment->story_id == $storyId) {
            $commentsByStoryId[] = $comment;
        }
    }
    return $commentsByStoryId;
}

function loadStories()
{
    // get request to the server
    $url = 'http://localhost/api/load-stories.php';
    $data = array('action' => 'loadStories');
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

    return json_decode($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C Wall </title>
    <script src="../js/error.js"></script>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/error.css">
    <?php include '../component/header.php'; ?> <!-- Include header view -->

</head>
<body>
    <div id="error-message" class="error-message">
        <?php
        $stories = loadStories();
        //if not string, show error message
        if (!is_array($stories)) {
            echo $stories->message;
        } else {
            $comments = loadComments();
            if (!is_array($comments)) {
                echo $comments->message;
            }
        }
        ?>
    </div>

    <section id="stories-container">
        <?php
        require '../component/displayStory.php';
        $stories = loadStories();
        foreach ($stories as $story){
            $storyObj = new Story($story->id, $story->content, $story->author, $story->date);
            $comments = getCommentsByStoryId($story->id);
            renderStory($storyObj, $comments);
        }
        ?>
    </section>
    <section id="submit-story">
        <label for="story">Votre post :</label>
        <textarea id="story" rows="4" required></textarea>
        <button id="submit-story-btn">Partager</button>
    </section>

    <footer>
        <?php include '../component/footer.php'; ?>
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Send an asynchronous request to check authentication status
        fetch('http://localhost/api/auth.php', {
            method: 'GET',
            credentials: 'include'
        })
            .then(response => {
                if (response.ok) {
                } else {
                    // User is not authenticated, prevent loading resources
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

        // Add event listener for submit button
        document.getElementById('submit-story-btn').addEventListener('click', function () {
            // Retrieve story content
            var storyContent = document.getElementById('story').value;

            // Send POST request to the server
            fetch('http://localhost/api/submit-story.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'story=' + encodeURIComponent(storyContent) + '&action=submit_story'
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

</script>