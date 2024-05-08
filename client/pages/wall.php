<?php
session_start();
// prevent writing to session from other requests
session_write_close();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
include '../../conf.php';
include "../js/conf_js.php";
include '../component/displayStory.php';
include '../component/form/storyForm.php';
include '../obj/comment.php';
include '../obj/story.php';
include '../component/dm_thread.php';
include '../component/navbar.php';
include '../component/form/messageForm.php';
include '../component/userBar.php';


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
    // Decode the JSON response
    return (json_decode($result));
}

$wallName = $_GET['username'] ?? $_SESSION['username'];
echo $wallName;
$wall = loadWall($wallName);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Mur </title>
    <script src="../js/error.js"></script>
    <script src="../js/dmSuggestion.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <script src="../js/submitStory.js"></script>
    <script src="../js/logout.js"></script>
    <script src="../js/like.js"></script>
    <script src="../js/replyForm.js"></script>
    <script src="../js/deleteButton.js"></script>
    <script src="../js/reportForm.js"></script>
    <script src="../js/commentButton.js"></script>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/error.css">
    <link rel="stylesheet" href="../css/userBar.css">
    <link rel="stylesheet" href="../css/storyForm.css">
    <link rel="stylesheet" href="../css/story.css">
    <link rel="stylesheet" href="../css/replyForm.css">
    <link rel="stylesheet" href="../css/likeButton.css">
    <link rel="stylesheet" href="../css/deleteButton.css">
    <link rel="stylesheet" href="../css/reportForm.css">
    <link rel="stylesheet" href="../css/commentButton.css">
    <link rel="stylesheet" href="../css/replies.css">
</head>
<?php include '../component/header.php'; ?>
<?php displayNavBar(); ?>
<body>
<div id="error-message" class="error-message">

</div>
<?php if ($wallName === $_SESSION['username']) : ?>
    <h1>Bienvenue sur votre Mur <?php echo htmlspecialchars($wallName); ?></h1>
<?php else : ?>
    <h1>Mur de <?php echo htmlspecialchars($wallName); ?></h1>
<?php endif; ?>
<div class="container">

    <div class="first-section">
        <?php displayUserBar("wall.php?username="); ?>
    </div>
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
            <?php if ($wallName !== $_SESSION['username']) : ?>
                <button id="follow-button" class="<?php echo $wall->is_followed ? 'unfollow-button' : 'follow-button'; ?>">
                    <?php echo $wall->is_followed ? 'Unfollow' : 'Follow'; ?>
                </button>
            <?php endif; ?>
        </div>
        <?php displayStoryForm(); ?>
        <section id="stories-container">
            <?php
            foreach ($wall->stories  as $storyData) {
                // Extract story data
                $story = $storyData;
                $storyObj = new Story($story->id, $story->content, $story->author, $story->created_at, $story->like_count, $story->story_image);

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

        fetch('<?php echo API_PATH?>/submit/submitFollow.php', {
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

        fetch('<?php echo API_PATH; ?>/load/loadBanner.php?username=' + encodeURIComponent(username))
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
                        banner.src = `<?php echo API_PATH?>/uploads/profile_banner/${data.banner_picture}`;
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

        fetch('<?php echo API_PATH; ?>/update/updateBanner.php', {
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
                }
            })
            .catch(error => {
                console.error('An error occurred during the banner upload:', error); // Handle exceptions
            });
    });
</script>
<style>
    /* CSS for the banner and profile picture to mimic Twitter */
    .banner-container {
        position: relative;
        width: 100%; /* Full width */
        height: 300px; /* Fixed height for the banner */
        margin-bottom: 10px; /* Space between sections */
    }
    #banner {
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        object-fit: cover; /* Maintains aspect ratio */
        object-position: center; /* Centers the image */
        border-radius: 10px; /* Rounded corners */
        border: 2px solid; /* Border for contrast */
    }
    .banner-profile-picture {
        position: absolute;
        bottom: 50px; /* Positioned above the bottom of the banner */
        left: 20px; /* Positioned from the left edge */
        border-radius: 50%; /* Circular shape */
        border: 3px solid white; /* Border for contrast */
        width: 80px; /* Size of the profile picture */
        height: 80px; /* Maintain a square shape */
        z-index: 2; /* Ensures it appears above the banner */
    }
    .edit-profile {
        position: absolute;
        bottom: 10px; /* Above the banner bottom */
        right: 20px; /* Positioned from the right edge */
        background-color: #b6bbc4; /* Light gray background */
        color: #0c2d57; /* Dark text color */
        padding: 5px 10px; /* Padding for spacing */
        border-radius: 5px; /* Rounded corners */
        text-decoration: none; /* No underline */
        z-index: 2; /* Ensures it appears above the banner */
    }

    .edit-profile:hover {
        background-color: #fc6736; /* Change color on hover */
        color: white; /* White text on hover */
    }
    /* Edit banner button */
    #change-banner-button {
        position: absolute; /* Position relative to the banner */
        bottom: 10px; /* Above the bottom of the banner */
        left: 20px; /* Positioned from the left edge */
        background-color: #b6bbc4; /* Light gray background */
        color: #0c2d57; /* Dark text color */
        padding: 5px 10px; /* Padding for spacing */
        border-radius: 5px; /* Rounded corners */
        border: none; /* No border */
        cursor: pointer; /* Change cursor to pointer on hover */
        z-index: 2; /* Ensures it appears above the banner */
    }

    .account-info {
        position: relative; /* Relative to its container */
        background-color: #b6bbc4; /* Light gray background */
        color: #0c2d57; /* Dark text color */
        padding: 10px; /* Padding for spacing */
        border: 2px solid; /* Border for emphasis */
        border-radius: 10px; /* Rounded corners */
        margin-bottom: 10px; /* Space between sections */
    }


    .account-info h2 {
        margin-bottom: 5px; /* Space below the heading */
    }

    .account-info p {
        margin-bottom: 5px; /* Space between paragraphs */
    }
    /* Adjust for smaller screens */
    @media (max-width: 600px) {
        .banner-container {
            height: 200px; /* Reduce height for smaller screens */
        }

        .banner-profile-picture {
            width: 60px; /* Reduce size of profile picture */
            height: 60px; /* Maintain square shape */
        }

        .edit-profile {
            bottom: 5px; /* Adjust position */
            right: 10px; /* Adjust position */
        }

        .account-info {
            padding: 5px; /* Reduce padding */
            border-radius: 5px; /* Adjust rounded corners */
        }
    }


    /* Default style for follow/unfollow buttons */
    .follow-button, .unfollow-button {
        border: none; /* No border */
        border-radius: 5px; /* Rounded corners */
        padding: 8px 16px; /* Padding for a comfortable click area */
        cursor: pointer; /* Change cursor to pointer on hover */
        font-weight: bold; /* Bold text for emphasis */
    }

    /* Style for the 'Follow' button */
    .follow-button {
        background-color: #0c2d57; /* Green for following */
        color: white; /* White text for contrast */
        transition: background-color 0.3s; /* Smooth transition on hover */
    }

    /* Hover effect for the 'Follow' button */
    .follow-button:hover {
        background-color: #0c2d57; /* Darker green on hover */
    }

    /* Style for the 'Unfollow' button */
    .unfollow-button {
        background-color: red; /* Red for unfollowing */
        color: white; /* White text for contrast */
        transition: background-color 0.3s; /* Smooth transition on hover */
    }

    /* Hover effect for the 'Unfollow' button */
    .unfollow-button:hover {
        background-color: darkred; /* Darker red on hover */
    }

    /* Disabled style for when the button shouldn't be clickable */
    .disabled-button {
        background-color: gray; /* Gray color for disabled state */
        color: lightgray; /* Lighter text to indicate disabled */
        cursor: not-allowed; /* Cursor indicates disabled */
    }

</style>


<?php include '../component/footer.php'; ?>

