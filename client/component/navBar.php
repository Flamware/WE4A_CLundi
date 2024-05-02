<?php
/**
 * Display the navigation bar
 */

//check if user is
function displayNavBar()
{
    ?>
    <link rel="stylesheet" href="../css/navbar.css">
    <script src="../js/logout.js"></script>
    <nav class="navbar">
    <button class="expand-button" onclick="showMenu()"><i class="fa fa-bars"></i></button>
    <div id="navbar-content" class="content" style="display: none;">
        <a class="link" href="main.php">Feed</a>
        <a class="link" href="wall.php">mur</a>
        <a class="link" href="messages.php">Messages</a>
        <a class="link" href="account.php">Compte</a>
        <a class="link" href="statistics.php">Stats</a>
        <a class="link" href="about.php">À propos</a>
        <a class="link" href="admin.php">Admin</a>
        <!-- Link to logout page triggering logout function -->
        <a class="link" href="" onclick="logout()">Déconnexion</a>
    </div>
</nav>

<script>
    function showMenu() {
        let navbar = document.getElementById("navbar-content");
        const current = navbar.style.getPropertyValue("display");
        navbar.style.setProperty("display", current == "none" ? "block" : "none");
    }
</script>
<?php
}