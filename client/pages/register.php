<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte</title>
    <link rel="stylesheet" href="../css/signup.css"> <!-- Link to styles.css -->
</head>
<body>
<div>
    <?php include '../component/header.php'; ?> <!-- Include header view -->
    <main>
        <section id="create-account-form">
            <h2>Créer un Compte</h2>
            <form action="" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required autocomplete="username">

                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required autocomplete="email">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">

                <label for="confirm-password">Confirmez le mot de passe :</label>
                <input type="password" id="confirm-password" name="confirm-password" required autocomplete="new-password">

                <button type="submit" name="register" value="true" id="create-account-button">Créer un Compte</button>
            </form>
            <?php
            include '../scripts/register.php';
            ?>
            <p>Déjà un compte ? <a href="login.php">Connexion</a></p>
        </section>
    </main>
    <?php include '../component/footer.php'; ?> <!-- Include footer view -->
</div>
</body>
</html>
