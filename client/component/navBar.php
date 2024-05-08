<?php
/**
 * Display the navigation bar with notification integration
 */

function displayNavBar() {
    ?>
    <!-- Navigation bar with notification icon -->
    <nav class="navbar">
        <button class="expand-button" onclick="showMenu()"><i class="fa fa-bars"></i></button>
        <!-- Notification icon with unseen count -->
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
    <?php
}
