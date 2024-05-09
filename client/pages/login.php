<?php
session_start();
session_write_close();
require '../../conf.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="../js/error.js"></script>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/error.css">
    <link rel="stylesheet" href="../css/pages/login.css">
    <link rel="stylesheet" href="../css/error.css">
</head>
<body>
<?php include '../component/header.php'; ?>

<div class="login">
    <main>
        <div id="error-message" class="error-message"></div>
        <section id="login-form-section">
            <form id="login-form" method="post" action="http://localhost/api/login.php">
                <h2>Connexion</h2>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="button-container">
                    <button id="submit-btn" type="submit">Se connecter</button>
                    <button type="button" onclick="window.location.href='register.php'">S'inscrire</button>
                    <!-- Link to signup page -->
                </div>
            </form>
        </section>
    </main>
    <?php include '../component/footer.php'; ?> <!-- Include footer view -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('login-form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission

            // Fetch API to submit login form data
            fetch('../../api/session/login.php', {
                method: 'POST',
                body: new FormData(this)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showError(data.message)
                        setTimeout(function () {
                            //go to main page
                            window.location.href = 'main.php';
                        }, 1000);
                    } else {
                        // If login unsuccessful, display error message
                        console.log("try again");
                        showError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred while processing your request. Please try again later.');
                });
        });
    });
</script>
</body>
</html>
