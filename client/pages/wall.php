<?php
session_start();
include '../component/displayStory.php';
include '../component/form/storyForm.php';
include '../obj/comment.php';
include '../obj/story.php';
include '../component/dm_thread.php';
include '../../api/conf.php';
include '../component/navbar.php';
include '../component/form/messageForm.php';

function getComments($comments, $storyId) {
    $commentsByStoryId = [];
    foreach ($comments as $comment) {
        if ($comment->story_id == $storyId) {
            $commentsByStoryId[] = $comment;
        }
    }
    return $commentsByStoryId;
}

function loadWall()
{
    $username = $_SESSION['username'] ?? null;
    // Construct the relative URL
    $url = API_PATH . '/load/loadWall.php';

    // Data to be sent in the request (if any)
    $data = array('action' => 'loadStories');

    // If a specific username is provided, add it to the data
    if ($username !== null) {
        $url .= '?username=' . urlencode($username);
    }

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
            'content' => http_build_query($data)
        )
    );

    // Create a stream context
    $context = stream_context_create($options);

    // Make the request and get the response
    $result = file_get_contents($url, false, $context);
    // Decode the JSON response
    return json_decode($result);
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C Wall </title>
    <script src="../js/error.js"></script>
    <script src="../js/auth.js"></script>
    <script src="../js/dmSuggestion.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/messageForm.css">
    <link rel="stylesheet" href="../css/error.css">
</head>
<?php include '../component/header.php'; ?>
<?php displayNavBar(); ?>
<body>
<div id="error-message" class="error-message">

</div>
<div class="container">

    <div class="first-section">

    </div>
    <div class="second-section">
            <div class="banner-container">
                <img src="../../api/uploads/profile_banner/default_banner.png" alt="banner" id="banner">
                <!-- Position the profile picture over the banner -->
                <img
                        src="../../api/uploads/profile_picture/default_profile_picture.png"
                        alt="Profile Picture"
                        class="profile-picture banner-profile-picture"
                        data-author-name="<?php echo htmlspecialchars($_SESSION['username']); ?>"
                >
                <div class="edit-profile">
                    <a href="account.php">Edit Profile</a>
                </div>
                <!-- Account Information -->
                <div class="account-info">
                    <h2>Account Information</h2>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
            </div>
        <?php displayStoryForm(); ?>
        <section id="stories-container">
            <?php
            $wall = loadWall();
            foreach ($wall->stories as $storyData) {
                // Extract story data
                $story = $storyData;
                $storyObj = new Story($story->id, $story->content, $story->author, $story->created_at, $story->like_count);

                // Get comments for the story
                $comments = getComments($wall->comments, $storyObj->id);

                // Display the story
                renderStory($storyObj, $comments);
            }
            ?>
        </section>
    </div>
    <div class="third-section">
        <div id="dm-threads">
            <?php
            displayMessageForm();
            ?>
        </div>
    </div>
</div>

</body>
<script src="../js/submitStory.js"></script>
<style>
    /* CSS for the banner and profile picture to mimic Twitter */
    .banner-container {
        position: relative;
        width: 100%; /* Use full width */
        height: 100%; /* Banner height */
    }
    #banner {
        width: 100%;
        height: 100%;
    }

    .banner-profile-picture {
        position: absolute;
        bottom: 23%; /* Move it to overlap the banner */
        left: 20px; /* Align to the left */
        border-radius: 50%;
        border: 3px solid white; /* Optional border */
        width: 80px;
        height: 80px;
    }
    .edit-profile {
        position: absolute;
        bottom: 0;
        right: 20px; /* Align to the right edge */
        background-color: #b6bbc4;
        color: #0c2d57;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
    }
    .edit-profile:hover {
        background-color: #fc6736;
        color: white;
    }
    .account-info {
        background-color: #b6bbc4;
        color: #0c2d57;
        padding: 10px;
        border: 2px solid;
        border-radius: 10px;
        margin-bottom: 5px;
    }
    .account-info h2 {
        margin-bottom: 5px;
    }
    .account-info p {
        margin-bottom: 5px;
    }
</style>


<?php include '../component/footer.php'; ?>

