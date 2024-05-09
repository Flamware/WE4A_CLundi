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

