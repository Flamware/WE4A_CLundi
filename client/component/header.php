<?php
/**
 * Display the header with notification integration
 */

$username = $_SESSION['username']?? 'Guest'; // Get the username from the session
?>
<header class="header">
    <div class="left">
        <a href="https://ae.utbm.fr/">
            <img src="../assets/ae.png" alt="Logo AE">
        </a>
        <a href="https://www.utbm.fr/">
            <img src="../assets/utbm.svg" alt="Logo UTBM">
        </a>
        <a href="main.php">
            <img src="../assets/utx.png" alt="Logo UTX">
        </a>
        <a href="main.php">
            <img src="../assets/utx_text.png" alt="UTX Text">
        </a>
    </div>
    <div class="right">
        <div class="header-user">
            <a href="account.php">
                <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($username) ?>">
            </a>
            <a href="account.php">
                <p><?= htmlspecialchars($username) ?></p>
            </a>
        </div>
        <div class="notification">
            <div class="notification-icon" onclick="toggleNotifications()">
                <i class="fa fa-bell"></i>
                <!-- Display the number of unseen notifications -->
                <span id="notification-count" class="notification-count">0</span>
            </div>
            <div id="notification-container" class="notification-container" style="display: none;">
                <!-- Notifications will be inserted here -->
            </div>
        </div>
    </div>
</header>

<script>
    function toggleNotifications() {
        const container = document.getElementById("notification-container");
        const isVisible = container.style.display === "block";
        container.style.display = isVisible ? "none" : "block"; // Toggle visibility

        if (!isVisible) {
            markNotificationsAsRead(); // Mark all as read when opening the notification box
        }
    }

    function markNotificationsAsRead() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo API_PATH; ?>/update/updateNotifications.php', true); // Endpoint for marking as read
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                console.log('Successfully marked notifications as read');
            } else {
                console.error('Failed to mark notifications as read:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error while marking notifications as read.');
        };

        xhr.send(); // No specific data needed
    }

    function fetchNotifications() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '<?php echo API_PATH; ?>/load/loadNotifications.php', true); // Fetch unseen count and notifications

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                const response = JSON.parse(xhr.responseText); // Parse the response
                document.getElementById('notification-count').innerText = response.unseen_count; // Update unseen count
                displayNotifications(response.notifications); // Display notifications
            } else {
                console.error('Failed to fetch notifications:', xhr.status, xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error while fetching notifications.');
        };

        xhr.send(); // Send the GET request
    }

    function displayNotifications(notifications) {
        const container = document.getElementById("notification-container");
        container.innerHTML = ''; // Clear existing content

        notifications.forEach(notification => {
            const notificationElement = document.createElement('div');
            notificationElement.className = 'notification-item';
            notificationElement.innerText = notification.message;

            const closeButton = document.createElement('span');
            closeButton.className = 'close-button';
            closeButton.innerText = 'x';

            closeButton.addEventListener('click', function() {
                deleteNotification(notification.id, notificationElement); // Handle deletion
            });

            notificationElement.appendChild(closeButton);
            container.appendChild(notificationElement); // Add to container
        });
    }

    function deleteNotification(notificationId, notificationElement) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo API_PATH; ?>/delete/notification.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                console.log('Successfully deleted notification');
                notificationElement.remove(); // Remove from UI
            } else {
                console.error('Failed to delete notification:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error while deleting notification.');
        };

        xhr.send('notification_id=' + notificationId); // Send the notification ID
    }

    fetchNotifications(); // Fetch on page load
</script>
<style>


    .header {
        display: flex; /* Ensure header uses flex layout */
        justify-content: space-between; /* Keep left and right sections at each end */
        align-items: center; /* Align vertically */
        background-color: #242038; /* Header background */
        color: #CAC4CE; /* Text color */
        padding: 10px 20px; /* Padding */
        border-radius: 0 0 10px 10px; /* Rounded bottom corners */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); /* Soft shadow */
    }
    .left {
        display: flex; /* Use flex layout */
        align-items: center; /* Align vertically */
        flex-grow: 1; /* Grow to occupy available space */
        justify-content: space-between; /* Even space between items */
        padding: 0 20px; /* Add padding on left and right for consistency */
    }
    .header img {
        max-width: 40px;
        height: auto;
    }

    /* Special case for UTBM logo */
    .header .utbm img {
        max-width: 80px; /* Larger logo */
        height: auto;
    }

    /* Right section of the header */
    .header .right {
        display: flex;
        align-items: center;
        gap: 20px; /* Increased gap for spacing */
    }

    /* User profile picture and text */
    .header-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-user img {
        border-radius: 50%; /* Circular profile picture */
    }

    /* Logout section */
    .logout {
        text-align: center; /* Center text alignment */
    }

    .logout a {
        text-decoration: none;
        color: #CAC4CE; /* Consistent text color */
        transition: color 0.3s; /* Smooth transition */
    }

    .logout a:hover {
        color: #fc6736; /* Change color on hover */
    }

    /* Notification icon and container */
    .notification {
        position: relative; /* For absolute positioning of inner elements */
    }

    .notification-icon {
        cursor: pointer; /* Indicate clickable element */
        position: relative;
    }

    .notification-count {
        position: absolute; /* Absolute position within the parent */
        top: -5px;
        right: -5px; /* Adjust to align with bell icon */
        background: #fc6736; /* Bright color for notification count */
        color: #ffffff; /* White text */
        border-radius: 50%; /* Circular count */
        padding: 2px 6px; /* Padding for count */
        font-size: 0.8rem; /* Smaller text */
    }

    /* Notification container */
    .notification-container {
        position: absolute; /* Relative to the parent */
        top: 100%; /* Position below the icon */
        right: 0; /* Align to the right */
        background: #ffffff; /* White background */
        border-radius: 8px; /* Soft corners */
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15); /* Soft shadow */
        width: 250px; /* Fixed width */
        max-height: 400px; /* Max height with overflow */
        overflow-y: auto; /* Enable scrolling */
        padding: 10px; /* Padding around content */
        transition: all 0.3s ease; /* Smooth transitions */
        z-index: 100; /* Ensure it appears above other elements */
        display: none; /* Default to hidden */
    }

    /* Individual notification items */
    .notification-item {
        display: flex;
        justify-content: space-between; /* Space between text and close button */
        align-items: center;
        padding: 10px; /* Padding within the notification */
        border-radius: 8px; /* Rounded corners */
        background: #f5f5f5; /* Light background */
        border: 1px solid #ccc; /* Border for separation */
        margin-bottom: 10px; /* Space between notifications */
        transition: all 0.3s; /* Smooth transitions */
    }

    .notification-item:hover {
        background: #f0f0f0; /* Lighter background on hover */
    }

    .close-button {
        cursor: pointer; /* Indicate clickable */
        color: #333; /* Dark color */
        font-weight: bold; /* Make it more visible */
    }

    .close-button:hover {
        color: #fc6736; /* Change color on hover */
    }


    /* Media queries for responsiveness */
    @media (max-width: 768px) {
        .header {
            flex-direction: column; /* Stack elements */
            padding: 10px; /* Adjust padding for smaller screens */
        }

        .header .right {
            flex-direction: column; /* Stack elements in a column */
            gap: 10px; /* Smaller gap for compact layout */
        }

        .header .user p {
            text-align: center; /* Center the text */
        }
    }


</style>
