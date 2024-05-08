<?php
session_start();
echo $_SESSION['username'];
include '../conf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="js/logout.js"></script>
    <title>CLUNDI</title>
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
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-image: url('assets/background.jpg');
    }

    #app {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    nav {
        background-color: #333;
        color: white;
        padding: 10px;
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    nav ul li {
        display: inline;
        margin-right: 10px;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
    }

    #content {
        flex-grow: 1;
        padding: 20px;
    }
</style>