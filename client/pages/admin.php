<?php
session_start();
session_write_close();
// Check if the user is authenticated
if (!isset($_SESSION['username']) || $_SESSION['admin'] !== 1) {
    // User is not authenticated, redirect to login page
    header('Location: login.php');
    exit();
}
require '../../conf.php';

include '../component/bar/navBar.php';
include '../component/bar/userBar.php';
include '../component/button/banButton.php';


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
        $reportAccount = isset($_GET['username']) ? $_GET['username'] : $_SESSION['username'];
        echo "<h2>Reports Overview of $reportAccount</h2>";
        if(!empty($reportInfo->banStatus)){
            echo "<h3>Ban Status</h3>";
            echo "<p><strong>$reportInfo->message</strong></p>";
            echo "<p><strong>Is Banned:</strong> " . ($reportInfo->banStatus->is_banned ? 'Yes' : 'No') . "</p>";
            echo "<p><strong>Ban Start:</strong> " . htmlentities($reportInfo->banStatus->ban_start) . "</p>";
            echo "<p><strong>Ban End:</strong> " . htmlentities($reportInfo->banStatus->ban_end) . "</p>";
        }
        // Display Reported Stories
        if (!empty($reportInfo->reportedStories)) {
            echo "<h3>Reported Stories</h3>";
            echo "<ul>"; // Using an unordered list
            foreach ($reportInfo->reportedStories as $report) {
                echo "<li>";
                echo "<strong>Story ID:</strong> <a href='#' onclick='displayStory(" . htmlentities($report->story_id) . ")'>" . htmlentities($report->story_id) . "</a><br>";
                echo "<strong>Content:</strong> " . htmlentities($report->content) . "<br>";
                echo "<strong>Reported By User ID:</strong> " . htmlentities($report->from) . "<br>";
                echo "<strong>Reported At:</strong> " . htmlentities($report->reported_at);
                echo "</li>";
                // add button to delete story or delete report associated with story
                echo "<button onclick='deleteStory(" . htmlentities($report->story_id) . ")'>Delete Story</button>";
                echo "<button onclick='deleteStoryReport(" . htmlentities($report->id) . ")'>Delete Report</button>";
            }
            echo "</ul>";
        }

        // Display Reported Comments
        if (!empty($reportInfo->reportedComments)) {
            echo "<h3>Reported Comments</h3>";
            echo "<ul>"; // Using an unordered list
            foreach ($reportInfo->reportedComments as $report) {
                echo "<li>";
                echo "<strong>Comment ID:</strong> <a href='#' onclick='displayComment(" . htmlentities($report->comment_id) . ")'>" . htmlentities($report->comment_id) . "</a><br>";
                echo "<strong>Content:</strong> " . htmlentities($report->content) . "<br>";
                echo "<strong>Reported By User ID:</strong> " . htmlentities($report->from) . "<br>";
                echo "<strong>Reported At:</strong> " . htmlentities($report->reported_at);
                echo "</li>";
                // add button to delete comment or delete report associated with comment
                echo "<button onclick='deleteComment(" . htmlentities($report->comment_id) . ")'>Delete Comment</button>";
                echo "<button onclick='deleteCommentReport(" . htmlentities($report->id) . ")'>Delete Report</button>";
            }
            echo "</ul>";
        }

        // Display Reported Messages
        if (!empty($reportInfo->reportedMessages)) {
            echo "<h3>Reported Messages</h3>";
            echo "<ul>"; // Using an unordered list
            foreach ($reportInfo->reportedMessages as $message) {
                echo "<li>";
                echo "<strong>Message ID:</strong> " . htmlentities($message->id) . "<br>";
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
    <script src="../js/fetchUsers.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <script src="../js/submitStory.js"></script>
    <script src="../js/logout.js"></script>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/error.css">
    <link rel="stylesheet" href="../css/pages/admin.css">
    <link rel="stylesheet" href="../css/error.css">
    <link rel="stylesheet" href="../css/bar/navbar.css">
    <link rel="stylesheet" href="../css/bar/userBar.css">
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
            <!-- Make admin button -->
            <button onclick="makeAdmin('<?php echo $userFetched; ?>')">Make Admin</button>
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
<?php include '../component/footer.php'; ?>
</html>

<script>
    // Function to display a story in a modal
    function displayStory(storyId) {
        const modal = document.getElementById("story-modal");
        const storyDetails = document.getElementById("story-details");
        var url = apiPath + `/load/loadStories.php?story_id=${storyId}`;
        // Fetch the story content from the server
        fetch(url)
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
                console.log('url is', url);
                console.error('Fetch error:', error); // Log the error
                showError('Error fetching story content.'); // Display error message
            });
    }

    // Function to display a comment in a modal
    function displayComment(commentId) {
        const modal = document.getElementById("story-modal");
        const storyDetails = document.getElementById("story-details");
        var url = apiPath + `/load/loadComments.php?comment_id=${commentId}`;
        // Fetch the comment content from the server
        fetch(url)
            .then(response => {
                if (!response.ok) { // Check for non-200 HTTP status
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Parse the JSON response
            })
            .then(data => {
                if (data.success) { // Ensure data contains a comment
                    const comment = data.comments[0];

                    // Display the comment content in the modal
                    storyDetails.innerHTML = `
                    <h2>Comment ID: ${comment.id}</h2>
                    <p><strong>Author:</strong> ${comment.author}</p>
                    <p><strong>Date:</strong> ${comment.created_at}</p>
                    <p><strong>Content:</strong> ${comment.content}</p>
                `;

                    modal.style.display = "flex"; // Show the modal
                } else {
                    // Handle unsuccessful response or missing comment data
                    console.error('Comment data missing or unsuccessful response:', data);
                    showError('Failed to load comment content.');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error); // Log the error
                showError('Error fetching comment content.'); // Display error message
            });
    }

    // Function to close the modal
    function closeModal() {
        const modal = document.getElementById("story-modal");
        modal.style.display = "none"; // Hide the modal
    }

    // Function to delete a story with post request
    function deleteStory(storyId) {
        var formData = new FormData();
        formData.append('story_id', storyId);
        fetch(apiPath + '/delete/story.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Story deleted successfully.');
                    location.reload();
                } else {
                    console.error('Delete story failed:', data);
                    alert(`Failed to delete story: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error deleting story:', error);
                alert('An error occurred while deleting the story.');
            });
    }
    function deleteStoryReport(reportId) {
        var formData = new FormData();
        formData.append('report_id', reportId);
        fetch(apiPath + '/delete/storyReport.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Report deleted successfully.');
                    location.reload();
                } else {
                    console.error('Delete report failed:', data);
                    alert(`Failed to delete report: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error deleting report:', error);
                alert('An error occurred while deleting the report.');
            });
    }
    function deleteComment(commentId) {
        var formData = new FormData();
        formData.append('comment_id', commentId);
        fetch(apiPath + '/delete/comment.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Comment deleted successfully.');
                    location.reload();
                } else {
                    console.error('Delete comment failed:', data);
                    alert(`Failed to delete comment: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error deleting comment:', error);
                alert('An error occurred while deleting the comment.');
            });
    }
    function deleteCommentReport(reportId) {
        var formData = new FormData();
        formData.append('report_id', reportId);
        fetch(apiPath + '/delete/commentReport.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Report deleted successfully.');
                    location.reload();
                } else {
                    console.error('Delete report failed:', data);
                    alert(`Failed to delete report: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error deleting report:', error);
                alert('An error occurred while deleting the report.');
            });
    }
    function makeAdmin(username) {
        var formData = new FormData();
        formData.append('username', username);
        fetch(apiPath + '/update/admin.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showError(data.message);
                } else {
                    console.error('Make admin failed:', data);
                    showError(`Failed to make user admin: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error making user admin:', error);
                showError('An error occurred while making the user admin.');
            });
    }
</script>
