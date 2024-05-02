<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}
require '../component/navbar.php';
require '../component/userBar.php';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C Wall </title>
    <script src="../js/error.js"></script>
    <script src="../js/auth.js"></script>
    <script src="../js/dmSuggestion.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <script src="../js/submitStory.js"></script>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/messageForm.css">
    <link rel="stylesheet" href="../css/error.css">
</head>
<?php include '../component/header.php'; ?>
<body>
<?php displayNavBar();?>
    <h1>Admin Page</h1>
    <div id="error-message" class="error-message">

    </div>
    <div class="container">
    <div class="first-section">
    </div>
    <div class="second-section">
        <h1>Chercher un utilisateur</h1>
        <?php displayUserBar('admin.php?username='); ?>
    </div>

    <section id="stories-container">
