<?php
session_start();
require '../component/navbar.php';
require '../component/userBar.php';
include '../../api/conf.php';
include '../component/button/banButton.php';
// Check if the user is authenticated
if (!isset($_SESSION['username']) || $_SESSION['admin'] !== 1) {
    // User is not authenticated, redirect to login page
    header('Location: /client');
    exit();
}
session_write_close();

$userFetched = $_GET['username'] ?? $_SESSION['username'];

function loadReportInfo($username)
{
    // Construct the relative URL
    $url = API_PATH . '/load/loadReportInfo.php?username=' . urlencode($username);
    // Data to be sent in the request (if any)
    $data = array('username' => $username);
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
$reportInfo = loadReportInfo($userFetched);


// Function to display report information in a structured manner
function displayReportInfo($reportInfo)
{
    // Check if the data is valid and contains information
    if ($reportInfo && $reportInfo->success) {
        echo "<h2>Reports Overview</h2>";
        if(!empty($reportInfo->banStatus)){
            echo "<h3>Ban Status</h3>";
            echo "<p><strong>Is Banned:</strong> " . ($reportInfo->banStatus->is_banned ? 'Yes' : 'No') . "</p>";
            echo "<p><strong>Ban Start:</strong> " . htmlentities($reportInfo->banStatus->ban_start) . "</p>";
            echo "<p><strong>Ban End:</strong> " . htmlentities($reportInfo->banStatus->ban_end) . "</p>";
        }
        // Display Reported Stories
        if (!empty($reportInfo->reportedStories)) {
            echo "<h3>Reported Stories</h3>";
            echo "<ul>"; // Using an unordered list
            foreach ($reportInfo->reportedStories as $story) {
                echo "<li>";
                echo "<strong>Story ID:</strong> <a href='#' onclick='displayStory(" . htmlentities($story->story_id) . ")'>" . htmlentities($story->story_id) . "</a><br>";
                echo "<strong>Content:</strong> " . htmlentities($story->content) . "<br>";
                echo "<strong>Reported By User ID:</strong> " . htmlentities($story->from) . "<br>";
                echo "<strong>Reported At:</strong> " . htmlentities($story->reported_at);
                echo "</li>";
            }
            echo "</ul>";
        }

        // Display Reported Comments
        if (!empty($reportInfo->reportedComments)) {
            echo "<h3>Reported Comments</h3>";
            echo "<ul>"; // Using an unordered list
            foreach ($reportInfo->reportedComments as $comment) {
                echo "<li>";
                echo "<strong>Comment ID:</strong> " . htmlentities($comment->comment_id) . "<br>";
                echo "<strong>Content:</strong> " . htmlentities($comment->content) . "<br>";
                echo "<strong>Reported By User ID:</strong> " . htmlentities($comment->from) . "<br>";
                echo "<strong>Reported At:</strong> " . htmlentities($comment->reported_at);
                echo "</li>";
            }
            echo "</ul>";
        }

        // Display Reported Messages
        if (!empty($reportInfo->reportedMessages)) {
            echo "<h3>Reported Messages</h3>";
            echo "<ul>"; // Using an unordered list
            foreach ($reportInfo->reportedMessages as $message) {
                echo "<li>";
                echo "<strong>Message ID:</strong> " . htmlentities($message->message_id) . "<br>";
                echo "<strong>Content:</strong> " . htmlentities($message->content) . "<br>";
                echo "<strong>Reported By User ID:</strong> " . htmlentities($message->from) . "<br>";
                echo "<strong>Reported At:</strong> " . htmlentities($message->reported_at);
                echo "</li>";
            }
            echo "</ul>";
        }

        // If no reports are found
        if (empty($reportInfo->reportedStories) && empty($reportInfo->reportedComments) && empty($reportInfo->reportedMessages)) {
            echo "<p>No reports found.</p>";
        }
    } else {
        // Handle error cases or if the data is invalid
        echo "<p>Unable to load report information or no reports available.</p>";
    }
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
    <script src="../js/submitStory.js"></script>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/error.css">
</head>
<?php include '../component/header.php'; ?>
<body>
<?php displayNavBar(); ?>
<h1>Admin Page</h1>

<div class="main-container">

    <div class="left-section">
        <?php displayUserBar('admin.php?username='); ?>
    </div>

    <div class="right-section">
        <div id="error-message" class="error-message"></div>
        <div class="page-content"> <!-- Placeholder for fetched content -->
            <?php
            displayReportInfo($reportInfo); // Function call to display the fetched report information
            renderBanButton();
            ?>
        </div>
    </div>
</div>

<!-- Include the modal structure here -->
<div class="modal" id="story-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">×</span> <!-- Close button for the modal -->
        <div id="story-details"></div> <!-- Content of the story -->
    </div>
</div>
</body>
</html>

<script>
    // Function to open the modal and load the story content
    // Function to display a story in a modal
    function displayStory(storyId) {
        const modal = document.getElementById("story-modal");
        const storyDetails = document.getElementById("story-details");

        // Fetch the story content from the server
        fetch(`../../api/load/loadStories.php?story_id=${storyId}`) // Correct URL
            .then(response => {
                if (!response.ok) { // Check for non-200 HTTP status
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Parse the JSON response
            })
            .then(data => {
                if (data.success && data.story) { // Ensure data contains a story
                    const story = data.story;

                    // Display the story content in the modal
                    storyDetails.innerHTML = `
                    <h2>Story ID: ${story.id}</h2>
                    <p><strong>Author:</strong> ${story.author}</p>
                    <p><strong>Date:</strong> ${story.date}</p>
                    <p><strong>Content:</strong> ${story.content}</p>
                `;
                    modal.style.display = "flex"; // Show the modal
                } else {
                    // Handle unsuccessful response or missing story data
                    console.error('Story data missing or unsuccessful response:', data);
                    showError('Failed to load story content.');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error); // Log the error
                showError('Error fetching story content.'); // Display error message
            });
    }

    // Function to close the modal
    function closeModal() {
        const modal = document.getElementById("story-modal");
        modal.style.display = "none"; // Hide the modal
    }



</script>
