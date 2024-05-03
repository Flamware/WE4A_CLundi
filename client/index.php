<?php
session_start();
echo $_SESSION['username'];
include '../api/conf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="js/logout.js"></script>
    <title>My SPA</title>
</head>
<body>

<div id="app">
    <!-- Content will be loaded here, put links to other pages here -->
    <nav>
        <ul>
            <li><a href="pages/main.php">Accueil</a></li>
            <li><a href="pages/messages.php">Messages</a></li>
            <li><a href="pages/account.php">Mon Compte</a></li>
            <li><a href="pages/login.php">Se Connecter</a></li>
        </ul>
    </nav>
    <div id="content">
        <!-- This is where the dynamically loaded content will be injected -->
    </div>

</div>
</body>
</html>
