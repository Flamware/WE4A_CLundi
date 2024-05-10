<?php
session_start();
session_write_close();
require '../../conf.php';
include '../obj/comment.php';
include '../obj/story.php';
include '../component/dm_thread.php';
include '../component/bar/navBar.php';
include '../component/form/messageForm.php';
include '../component/form/storyForm.php';
require '../component/displayStory.php';
include '../component/bar/userBar.php';
include '../component/bar/searchBar.php';
require '../js/conf_js.php';
function getCommentsByStoryId($comments, $storyId) {
    $commentsByStoryId = [];
    foreach ($comments as $comment) {
        if ($comment['story_id'] == $storyId) {
            $commentsByStoryId[] = $comment;
        }
    }
    return $commentsByStoryId;
}
function loadStories($page, $search = null) {
    // Construct the relative URL with GET parameters
    $url = API_PATH . '/load/loadStories.php?page=' . urlencode($page) . '&query=' . urlencode($search);
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
$search = isset($_GET['search']) ? $_GET['search'] : null;
// Load stories for the given page
$data = loadStories($page, $search);
// Check if the response is valid
$stories = $data['stories'];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page </title>
    <script src="../js/error.js"></script>
    <script src="../js/fetchUsers.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <script src="../js/submitStory.js"></script>
    <script src="../js/logout.js"></script>
    <script src="../js/like.js"></script>
    <script src="../js/replyForm.js"></script>
    <script src="../js/deleteButton.js"></script>
    <script src="../js/reportForm.js"></script>
    <script src="../js/commentButton.js"></script>
    <script src="../js/searchStory.js"></script>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/pages/main.css">
    <link rel="stylesheet" href="../css/bar/navbar.css">
    <link rel="stylesheet" href="../css/bar/searchStoryBar.css">
    <link rel="stylesheet" href="../css/error.css">
    <link rel="stylesheet" href="../css/bar/userBar.css">
    <link rel="stylesheet" href="../css/form/storyForm.css">
    <link rel="stylesheet" href="../css/component/story.css">
    <link rel="stylesheet" href="../css/form/replyForm.css">
    <link rel="stylesheet" href="../css/button/likeButton.css">
    <link rel="stylesheet" href="../css/button/deleteButton.css">
    <link rel="stylesheet" href="../css/form/reportForm.css">
    <link rel="stylesheet" href="../css/button/commentButton.css">
    <link rel="stylesheet" href="../css/component/replies.css">
    <link rel="stylesheet" href="../css/form/messageForm.css">
</head>
<body>

<?php include '../component/header.php'; ?>
<?php
displayNavBar();
displaySearchBar();
?>
<h1>General</h1>
    <div id="error-message" class="error-message">
        <?php
        //if not string, show error message
        if (!is_array($stories)) {
            echo $stories;
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
            $storyObj = new Story(
                    $story['id'],
                    $story['content'],
                    $story['author'],
                    $story['date'],
                    $story['like_count'],
                    $story['story_image']
            );
            // define comment as an array of Comment objects
            $commentObjects = array();
            foreach ($story['comments'] as $comment) {
                $commentObj = new Comment(
                    $comment['id'],
                    $comment['story_id'],
                    $comment['parent_comment_id'] ?? null,
                    $comment['content'],
                    $comment['author'],
                    $comment['created_at'],
                    $comment['like_count'],
                );
                $commentObjects[] = $commentObj;
            }
            renderStory($storyObj, $commentObjects);
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
        <?php displayMessageForm(); ?>
    </div>
    </div>
    <?php include '../component/footer.php'; ?>

</body>

