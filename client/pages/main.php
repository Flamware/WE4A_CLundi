<?php
session_start(); // Start the session at the beginning of the file
include '../obj/comment.php';
include '../obj/story.php';

// Load stories from the server
function submitStory($story) {
    error_log("submitStory called at " . date('Y-m-d H:i:s') . " with story: " . $story);
    // post request to the server
    $url = 'http://localhost/submit-story.php';
    $data = array('story' => $story, 'username' => $_SESSION['username'], 'action' );
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
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
}

    // Load comments from the server
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


    // Handle story_display.php submission
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
    $comments = loadComments();

    ?>
<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $story = $_POST['story'];
    submitStory($story);
    // Redirect to the main page with get request
    header('Location: main.php');
    exit;
}
?>

    <div>
        <header>
            <link rel="stylesheet" href="../css/main.css"> <!-- Link to styles.css -->
            <?php include '../component/header.php'; ?> <!-- Include header view -->
        </header>

        <section id="stories-container">
            <?php include_once '../obj/story.php';
            foreach ($stories as $story){
                $storyObj = new Story($story->id, $story->content, $story->author, $story->date);
                $comments = getCommentsByStoryId($story->id);
                include '../component/story_display.php';
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

