<?php
session_start();
require '../conf.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur CLUNDI</title>
    <!-- Consolidate CSS files to reduce HTTP requests -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<header class="header">
    <div class="left">
        <a href="https://ae.utbm.fr/">
            <img src="assets/ae.png" alt="Logo AE">
        </a>
        <a href="https://www.utbm.fr/">
            <img src="assets/utbm.svg" alt="Logo UTBM">
        </a>
        <a href="pages/main.php">
            <!-- relative path to main.php -->
            <img src="assets/utx_text.png" alt="UTX Text">
        </a>
        <a href="pages/main.php">
            <img src="assets/utx.png" alt="UTX Text">
        </a>
    </div>

</header>

<body>

<div id="app">
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="pages/main.php">Accueil</a></li>
            <li><a href="pages/messages.php">Messages</a></li>
            <li><a href="pages/account.php">Mon Compte</a></li>
            <?php if (isset($_SESSION['username'])) { ?>
                <li><a href="pages/logout.php">Se Déconnecter</a></li>
            <?php }
            ?>
            <li><a href="pages/login.php">Se Connecter</a></li>
        </ul>
    </nav>

    <!-- Content Area -->
    <div id="content">
        <!-- Welcome Message and Navigation Options -->
        <h1>Bienvenue sur CLUNDI</h1>
        <p>Un réseau social pour les étudiants de l'Université de l'UTBM</p>

        <?php if (!isset($_SESSION['username'])) { ?>
            <p>Connectez-vous pour accéder à votre fil d'actualité <a href="pages/login.php">ici</a>.</p>
            <p>Pas de compte ? Inscrivez-vous <a href="pages/register.php">ici</a>.</p>
        <?php } else { ?>
            <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>! Votre fil d'actualité est disponible <a href="pages/main.php">ici</a>.</p>
        <?php } ?>
    </div>
</div>

<!-- Include the footer -->
<?php include 'component/footer.php'; ?>

<!-- JavaScript Files -->
<script src="js/logout.js"></script>
</body>
</html>
<style>
    /* General body styling */
    body {
        font-family: Arial, sans-serif;
        background: rgb(61,61,61);
        background: radial-gradient(circle, rgba(61,61,61,1) 0%, rgba(194,66,66,1) 50%, rgba(0,12,110,1) 100%);
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        display: flex; /* Flexbox for layout */
        flex-direction: column;
        min-height: 100vh; /* Ensure full viewport height */
    }

    /* App container */
    #app {
        display: flex; /* Flex layout */
        flex-direction: column; /* Vertical stacking */
        flex-grow: 1; /* Allow this to grow */
    }

    /* Navigation Bar Styling */
    nav {
        background-color: rgba(170, 184, 194, 0.5); /* Transparent background */
        backdrop-filter: blur(10px); /* Apply a blur effect */        color: white;
        text-align: center; /* Center the navigation links */
        padding: 10px 0; /* Add padding */

    }

    nav ul {
        list-style-type: none; /* Remove default list styles */
        padding: 0;
        margin: 0;
    }

    nav ul li {
        display: inline-block; /* Use inline-block for consistent spacing */
        margin-right: 15px;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        transition: color 0.3s; /* Smooth transition */
    }

    nav ul li a:hover {
        color: #ccc; /* Change color on hover */
    }

    /* Content area styling */
    #content {
        flex-grow: 1; /* Fill available space */
        padding: 20px; /* Add some padding */
        color: white; /* Ensure text visibility */
        text-align: center;
    }

    /* Heading and paragraph styling */
    h1 {
        font-size: 2em;
        margin-top: 20px; /* Add top margin */
    }

    p {
        font-size: 1.2em; /* Increase font size for readability */
        margin-top: 10px;
    }



</style>