<?php
session_start();
// Check if the user is authenticated
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header('Location: /client');
    exit();
}
session_write_close();

include '../obj/comment.php';
include '../obj/story.php';
include '../component/dm_thread.php';
include '../../conf.php';
include '../component/navbar.php';
include '../component/form/messageForm.php';
include '../component/form/storyForm.php';
require '../component/displayStory.php';
include '../component/userBar.php';
function loadComments() {
    // get request to the server
    $url = API_PATH . '/load/loadComments.php';
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
function loadStories($page) {
    // Construct the relative URL with GET parameters
    $url = API_PATH . '/load/loadStories.php?page=' . urlencode($page);
    // Headers for the request
    $headers = array(
        'Content-type: application/x-www-form-urlencoded',
        'Cookie: ' . http_build_query($_COOKIE, '', ';')
    );
    // Options for the HTTP request
    $options = array(
        'http' => array(
            'header' => $headers,
            'method' => 'GET',
        )
    );
    // Create a stream context
    $context = stream_context_create($options);
    // Make the request and get the response
    $result = file_get_contents($url, false, $context);
    // Decode the JSON response
    return json_decode($result, true); // true to return as associative array
}

// Get the current page number
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
// Load stories for the given page
$storiesData = loadStories($page);
$stories = $storiesData['stories'];

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C Wall </title>
    <script src="../js/error.js"></script>
    <script src="../js/dmSuggestion.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <script src="../js/submitStory.js"></script>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/messageForm.css">
    <link rel="stylesheet" href="../css/error.css">
</head>
<?php include '../component/header.php'; ?>
<?php displayNavBar(); ?>
<h1>Feed General</h1>
<body>
    <div id="error-message" class="error-message">
        <?php
        //if not string, show error message
        if (!is_array($stories)) {
            echo $stories;
        } else {
            $comments = loadComments();
            if (!is_array($comments)) {
                echo $comments->message;
            }
        }
        ?>
    </div>
    <div class="container">
    <div class="first-section">
        <?php displayUserBar("wall.php?username="); ?>
    </div>
    <div class="second-section">
        <?php displayStoryForm(); ?>
    <section id="stories-container">
        <?php
        foreach ($stories as $story) {
            $storyObj = new Story($story['id'], $story['content'], $story['author'], $story['date'], $story['like_count'], $story['story_image']);
            $comments = getCommentsByStoryId($story['id']);
            renderStory($storyObj, $comments);
        }
        ?>
        <div class="pagination">
            <?php
            // Previous page link
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "'>&laquo; Previous</a>";
            }
            // Next page link
            if (count($stories) == 10) {
                echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
            }
            ?>
        </div>
    </section>
    </div>
    <div class="third-section">
            <div id="dm-threads">
                <?php displayMessageForm(); ?>
            </div>
    </div>
    </div>
    <?php include '../component/footer.php'; ?>

</body>

