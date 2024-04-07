<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css"> <!-- Link to styles.css -->
</head>
<body>
<div class="login">
    <?php include '../component/header.php'; ?>

    <main>
        <section id="login-form">
            <h2>Connexion</h2>
            <div id="error-message" class="error-message">
                <?php
                // Display error message if any
                if (!empty($_SESSION['login_error'])) {
                    echo $_SESSION['login_error'];
                    unset($_SESSION['login_error']); // Clear the error message after displaying it
                }
                ?>
            </div> <!-- Error message container -->
            <form action="http://localhost/login.php" method="post">
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
</body>
</html>
