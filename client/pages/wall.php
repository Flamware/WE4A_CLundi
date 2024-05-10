<?php
session_start();
// prevent writing to session from other requests
session_write_close();
if (!isset($_SESSION['username'])&&!isset($_GET['username'])){
    header('Location: login.php');
    exit;
}
require '../../conf.php';
include '../obj/comment.php';
include '../obj/story.php';
include '../component/displayStory.php';
include '../component/form/storyForm.php';
include '../component/dm_thread.php';
include '../component/bar/navBar.php';
include '../component/form/messageForm.php';
include '../component/bar/userBar.php';


function getComments($comments, $storyId) {
    $commentsByStoryId = [];
    foreach ($comments as $comment) {
        if ($comment->story_id == $storyId) {
            $commentsByStoryId[] = $comment;
        }
    }
    return $commentsByStoryId;
}
function loadWall($wallName)
{
    // Construct the relative URL
    $url = API_PATH . '/load/loadWall.php';
    // Data to be sent in the request (if any)
    $data = array('action' => 'loadStories');

    // If a specific username is provided, add it to the data
    if ($wallName !== null) {
        $url .= '?username=' . urlencode($wallName);
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

    return (json_decode($result));
}

$wallName = $_GET['username'] ?? $_SESSION['username'];
$wall = loadWall($wallName);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($_SESSION['username']) && $wallName === $_SESSION['username']) : ?>
        <title>Votre Mur</title>
    <?php else : ?>
        <title>Mur de <?php echo htmlspecialchars($wallName); ?></title>
    <?php endif; ?>
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
    <link rel="stylesheet" href="../css/pages/wall.css">
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
<?php include '../component/header.php'; ?>
<?php displayNavBar(); ?>
<body>
<div id="error-message" class="error-message">

</div>
<?php if (isset($_SESSION['username']) && $wallName === $_SESSION['username']) : ?>
    <h1>Bienvenue sur votre Mur <?php echo htmlspecialchars($wallName); ?></h1>
<?php else : ?>
    <h1>Mur de <?php echo htmlspecialchars($wallName); ?></h1>
<?php endif; ?>
<div class="container">

    <div class="first-section">
        <?php displayUserBar("wall.php?username="); ?>
    </div>
    <?php
    if ($wall->message == null) : ?>

        <p>Utilisateur Inexistant.</p>
    <?php else : ?>
    <div class="second-section">
            <div class="banner-container">
                <!-- Banner image, use function to fetch the banner -->
                <img
                        src="../assets/default_banner.jpg"
                        alt="Banner"
                        id="banner"
                        class="banner"
                >
                <!-- Profile picture -->
                <img
                        src="../assets/profile_picture.png"
                        alt="Profile Picture"
                        class="profile-picture banner-profile-picture"
                        data-author-name="<?php echo htmlspecialchars($wallName); ?>"
                >
                <?php if ($wallName === $_SESSION['username']) : ?>
                <button id="change-banner-button">Changer ma banniere</button> <!-- Button to change the banner -->
                <input type="file" id="banner-file-input" accept="image/*" style="display: none;">
                <div class="edit-profile">
                    <a href="account.php">Edit Profile</a>
                </div>
                <?php endif; ?>

            </div>
        <div class="account-info">
            <h2>Account Information</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($wallName); ?></p>
            <!-- Follow/Unfollow Button -->
            <?php if (isset($_SESSION['username']) && $wallName !== $_SESSION['username']) : ?>
                <button id="follow-button" class="<?php echo $wall->is_followed ? 'unfollow-button' : 'follow-button'; ?>">
                    <?php echo $wall->is_followed ? 'Unfollow' : 'Follow'; ?>
                </button>
            <?php endif; ?>
        </div>
        <?php displayStoryForm(); ?>
        <section id="stories-container">
            <?php
            if (empty($wall->stories)) {
                echo '<p>No stories found.</p>';
            }
            else {
                foreach ($wall->stories as $storyData) {
                    // Extract story data
                    $story = $storyData;
                    $storyObj = new Story($story->id, $story->content, $story->author, $story->created_at, $story->like_count, $story->story_image);

                    // Get comments for the story
                    $comments = getComments($wall->comments, $storyObj->id);

                    // Display the story
                    renderStory($storyObj, $comments);
                }
            }
            ?>
        </section>
    </div>
    <?php endif; ?>
    <div class="third-section">
        <div id="dm-threads">
            <?php
            displayMessageForm();
            ?>
        </div>
    </div>
</div>

</body>
<script>
    // Fetch the user's banner when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        fetchBanner(); // Fetch the banner for the current user
    });

    // Attach event listener to the follow button
    const followButton = document.getElementById('follow-button');
    if (followButton) {
        followButton.addEventListener('click', toggleFollow);  // Attach event listener to the button
    }

    document.getElementById('change-banner-button').addEventListener('click', function () {
        // Trigger the file input when the button is clicked
        document.getElementById('banner-file-input').click();
    });

    // Function to follow/unfollow a user using POST
    function toggleFollow() {
        const followButton = document.getElementById('follow-button');
        const username = '<?php echo htmlspecialchars($wallName); ?>';

        fetch(apiPath+'/submit/submitFollow.php', {
            method: 'POST',  // POST request
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',  // Ensure correct content type
            },
            body: `username=${encodeURIComponent(username)}`,  // Send username in the body
        })
            .then(response => response.json())  // Parse JSON response
            .then(data => {
                if (data.success) {
                    // Update button text and class based on follow/unfollow
                    followButton.textContent = data.isFollowing ? 'Unfollow' : 'Follow';
                    followButton.classList.toggle('unfollow-button', data.isFollowing);
                    followButton.classList.toggle('follow-button', !data.isFollowing);
                } else {
                    console.error(data.message); // Handle error messages
                    showError(data.message); // Show error message to the user
                }
            })
            .catch(error => {
                console.error('An error occurred:', error); // Handle network or other errors
            });
    }
    // Fetch user Banner
    function fetchBanner() {
        console.log('Fetching banner...');
        const banner = document.getElementById('banner'); // Corrected ID here
        const username = '<?php echo htmlspecialchars($wallName); ?>';

        fetch(apiPath + '/load/loadBanner.php?username=' + encodeURIComponent(username))
            .then(response => {
                if (!response.ok) { // Handle non-200 HTTP responses
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Parse the JSON response
            })
            .then(data => {
                if (data.success) { // If the response is successful
                    // Update the banner image source if available
                    if (data.banner_picture) {
                        console.log('Banner data:', data);
                        banner.src = apiPath + `/uploads/profile_banner/${data.banner_picture}`;
                    }
                    else
                    {
                        console.log('Banner data:', data);
                        banner.src = '../assets/default_banner.png';
                    }
                } else {
                    console.error('Banner data missing or unsuccessful response:', data); // Handle errors
                }
            })
            .catch(error => {
                console.error('An error occurred while fetching the banner:', error); // Handle exceptions
            });
    }


    document.getElementById('banner-file-input').addEventListener('change', function (event) {
        const file = event.target.files[0]; // Get the selected file
        const formData = new FormData(); // Create a FormData object
        formData.append('banner', file); // Append the file to the form data

        fetch(apiPath + '/update/updateBanner.php', {
            method: 'POST', // POST request
            body: formData // Send the form data
        })
            .then(response => response.json()) // Parse the JSON response
            .then(data => {
                if (data.success) {
                    // If the upload was successful, fetch the new banner
                    fetchBanner();
                } else {
                    console.error('Banner upload failed:', data.message); // Handle errors
                    showError(data.message); // Show error message to the user
                }
            })
            .catch(error => {
                console.error('An error occurred during the banner upload:', error); // Handle exceptions
            });
    });
</script>



<?php include '../component/footer.php'; ?>

