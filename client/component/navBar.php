<?php
session_start();
session_write_close();
function displayNavBar() {
    ?>
    <!-- Navigation bar with submenu for feeds -->
    <nav class="navbar">
        <button class="expand-button" onclick="toggleMenu()"><i class="fa fa-bars"></i></button>
        <div id="navbar-content" class="content">
            <?php
            if (isset($_SESSION['user_id']) && $_SESSION['admin'] == 1)
                echo '<a class="link" href="admin.php">Admin</a>';
            ?>
            <a class="link" href="main.php">Accueil</a>
            <div class="dropdown">
                <a class="link">Feed</a> <!-- The visible label for the submenu -->
                <div class="dropdown-content"> <!-- Submenu -->
                    <a class="link" href="feed.php">Votre Feed</a>
                    <a class="link" href="wall.php">Votre Mur</a>
                    <a class="link" href="suggestion.php">Découvrir</a>
                </div>
            </div>
            <div class="dropdown">
                <a class="link">Compte</a>
                <div class="dropdown-content">
                    <a class="link" href="account.php">Mon compte</a>
                    <a class="link" href="statistics.php">Statistiques</a>
                    <a class="link" href="messages.php">Messages</a>
                </div>
            </div>
            <?php if(isset($_SESSION['user_id'])) { ?>
                <a class="link" onclick="logout()">Déconnexion</a>
            <?php } else { ?>
                <a class="link" href="login.php">Connexion</a>
            <?php } ?>

            <a class="link" href="about.php">À propos</a>
        </div>
    </nav>
    <?php
}
?>