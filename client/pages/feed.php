<?php
require '../../conf.php';
session_start();
session_write_close();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
include "../component/navbar.php";
include "../component/userBar.php";
include "../component/form/messageForm.php";
include "../component/form/storyForm.php";
include "../obj/comment.php";
include "../obj/story.php";
include "../component/displayStory.php";

// Get the current page number or default to 1
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
function getCommentsByStoryId($comments, $storyId) {
    $commentsByStoryId = [];
    foreach ($comments as $comment) {
        if ($comment['story_id'] == $storyId) {
            $commentsByStoryId[] = $comment;
        }
    }
    return $commentsByStoryId;
}
function loadFeed($page) {
    // Construct the relative URL to fetch the feed data with the page parameter
    $url = API_PATH . '/load/loadFeed.php?page=' . urlencode($page);

    // Headers for the request
    $headers = array(
        'Content-type: application/x-www-form-urlencoded',
        'Cookie: ' . http_build_query($_COOKIE, '', ';') // Pass current session cookies
    );

    // Options for the HTTP request
    $options = array(
        'http' => array(
            'header' => $headers,
            'method' => 'GET',
            'content' => '' // No additional data is required
        )
    );

    // Create a stream context and fetch the feed
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    // Decode the JSON response as an associative array
    return json_decode($result, true); // Always return an associative array
}

$feed = loadFeed($page);

if ($feed === null || !isset($feed['success']) || !$feed['success']) {
    echo "Error loading feed.";
    exit;
}

$stories = $feed['stories'];
$comments = $feed['comments'];


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Feed</title>
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
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/pages/main.css">
    <link rel="stylesheet" href="../css/bar/navbar.css">
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
<?php displayNavBar(); ?>
<h1>Votre Feed</h1>

<div class="container">
    <div class="first-section">
        <?php displayUserBar("wall.php?username="); ?>
    </div>
    <div class="second-section">
        <section id="stories-container">
            <?php
            foreach ($stories as $story) {
                $storyObj = new Story(
                    $story['id'],
                    $story['content'],
                    $story['author'],
                    $story['created_at'],
                    $story['like_count'],
                    $story['story_image']
                );
                $comments = getCommentsByStoryId($comments,$story['id']);
                // define comment as an array of Comment objects
                $commentObjects = array();
                foreach ($comments as $comment) {
                    $commentObj = new Comment(
                        $comment['id'],
                        $comment['story_id'],
                        $comment['parent_comment_id'] ?? null,
                        $comment['content'],
                        $comment['author'],
                        $comment['created_at'],
                        $comment['like_count']
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

                // Next page link (assume there are at least 10 stories per page)
                if (is_array($stories) && count($stories) == 10) {
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
