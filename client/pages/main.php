<?php
include '../obj/comment.php';
include '../obj/story.php';
function loadComments() {
    // get request to the server
    $url = 'http://localhost/load-comments.php';
    $data = array('action' => 'loadComments');
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
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
        $error = 'Impossible de charger les commentaires, veuillez réessayer.';
        echo $error;
    }
    return json_decode($result);
}


// Handle displayStory.php submission
function loadStories() {
    // get request to the server
    $url = 'http://localhost/load-stories.php';
    $data = array('action' => 'loadStories');
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
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
        $error = 'Impossible de charger les stories, veuillez réessayer.';
        echo $error;
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

// Load stories and comments
$stories = loadStories();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $story = $_POST['story'];
    // post request to the server
    $url = 'http://localhost/submit-story.php';
    $data = array('story' => $story);
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "Cookie: " . session_name() . "=" . session_id() . "\r\n", // Include session cookie
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    // Create a stream context
    $context = stream_context_create($options);
    // Make the request and get the response
    $result = file_get_contents($url, false, $context);
    //if the request failed show an error message
    if ($result === FALSE) {
        $error = 'Impossible de partager votre post, veuillez réessayer.';
        echo $error;
    }
    echo $result;

}
?>

<div>
    <header>
        <link rel="stylesheet" href="../css/main.css"> <!-- Link to styles.css -->
        <?php include '../component/header.php'; ?> <!-- Include header view -->
    </header>


    <section id="stories-container">
        <?php
        foreach ($stories as $story){
            $storyObj = new Story($story->id, $story->content, $story->author, $story->date);
            $comments = getCommentsByStoryId($story->id);
            include '../component/displayStory.php';
        }
        ?>
    </section>
    <section id="submit-story">
        <form action="" method="post">
            <label for="story">Votre post :</label>
            <textarea name="story" id="story" rows="4" required></textarea>
            <button type="submit">Partager</button>
        </form>
    </section>

    <footer>
        <?php include '../component/footer.php'; ?>
    </footer>
</div>
