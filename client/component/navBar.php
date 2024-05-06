<?php
/**
 * Display the navigation bar with notification integration
 */

function displayNavBar() {
    ?>
    <!-- Include CSS and JavaScript for the navbar -->
    <link rel="stylesheet" href="../css/navbar.css">
    <script src="../js/logout.js"></script>

    <!-- Navigation bar with notification icon -->
    <nav class="navbar">
        <button class="expand-button" onclick="showMenu()"><i class="fa fa-bars"></i></button>
        <!-- Notification icon with unseen count -->
        <div class="notification-icon" onclick="toggleNotifications()">
            <i class="fa fa-bell"></i>
            <!-- Display the number of unseen notifications -->
            <span id="notification-count" class="notification-count">0</span>
        </div>
        <div id="navbar-content" class="content" style="display: none;">
            <a class="link" href="main.php">General</a>
            <a class="link" href="feed.php">Votre Feed</a>
            <a class="link" href="wall.php">Votre Mur</a>
            <a class="link" href="messages.php">Messages</a>
            <a class="link" href="account.php">Compte</a>
            <a class="link" href="statistics.php">Stats</a>
            <a class="link" href="about.php">À propos</a>
            <a class="link" href="admin.php">Admin</a>
            <a class="link" href="" onclick="logout()">Déconnexion</a>
        </div>
    </nav>

    <!-- Notification box (initially hidden) -->
    <div id="notification-container" class="notification-container" style="display: none;">
        <!-- Notifications will be dynamically inserted here -->
    </div>

    <!-- JavaScript for navbar and notification handling -->
    <script>
        function showMenu() {
            let navbar = document.getElementById("navbar-content");
            const current = navbar.style.display;
            navbar.style.display = current === "none" ? "block" : "none";
        }

        function toggleNotifications() {
            let container = document.getElementById("notification-container");
            const current = container.style.display;
            container.style.display = current === "none" ? "block" : "none";

            if (container.style.display === "block") {
                markNotificationsAsRead(); // Mark all as read when opening the notification box
            }
        }

        function markNotificationsAsRead() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo API_PATH ?>/update/updateNotifications.php', true); // Endpoint for marking as read
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Correct content type

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    console.log('Successfully marked notifications as read');
                } else {
                    console.error('Failed to mark notifications as read:', xhr.statusText); // Handle errors
                }
            };

            xhr.onerror = function() {
                console.error('Network error while marking notifications as read.'); // Handle network-related errors
            };

            xhr.send(); // No specific data needed
        }

        function fetchNotifications() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '<?php echo API_PATH ?>/load/loadNotifications.php', true); // Fetch unseen count and notifications

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText); // Parse the response
                    displayNotifications(response.notifications); // Display the notifications
                    document.getElementById('notification-count').innerText = response.unseen_count;// Update the unseen count
                    console.log('unsen count : ', response.unseen_count);
                } else {
                    console.error('Failed to fetch notifications:', xhr.status, xhr.statusText); // Handle errors
                }
            };

            xhr.onerror = function() {
                console.error('Network error while fetching notifications.'); // Handle network-related errors
            };

            xhr.send(); // Send the GET request
        }

        function displayNotifications(notifications) {
            const notificationContainer = document.getElementById("notification-container");
            notificationContainer.innerHTML = ''; // Clear existing content

            notifications.forEach(notification => {
                const notificationElement = document.createElement('div');
                notificationElement.className = 'notification';
                notificationElement.innerText = notification.message; // Display the notification message

                const closeButton = document.createElement('span');
                closeButton.className = 'close-button';
                closeButton.innerText = 'x';

                closeButton.addEventListener('click', function() {
                    deleteNotification(notification.id, notificationElement); // Handle deletion
                });

                notificationElement.appendChild(closeButton);
                notificationContainer.appendChild(notificationElement); // Add to container
            });
        }

        function deleteNotification(notificationId, notificationElement) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo API_PATH ?>/delete/notification.php', true); // Endpoint for deleting a notification
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Correct content type

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    console.log('Successfully deleted notification');
                    notificationElement.remove(); // Remove the notification from the UI
                } else {
                    console.error('Failed to delete notification:', xhr.statusText); // Handle errors
                }
            };

            xhr.onerror = function() {
                console.error('Network error while deleting notification.'); // Handle network-related errors
            };

            xhr.send('notification_id=' + notificationId); // Send the notification ID as POST data
        }

        // Fetch notifications and unseen count on page load
        fetchNotifications(); // Call this function to load notifications
    </script>

    <?php
}
