<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte</title>
    <link rel="stylesheet" href="../css/pages/signup.css"> <!-- Link to styles.css -->
    <link rel="stylesheet" href="../css/bar/navbar.css"> <!-- Link to nav.css -->
</head>

<body>
<div>
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
            <p>Déjà un compte ? <a href="login.php">Connexion</a></p>
        </section>
    </main>
    <?php include '../component/footer.php'; ?> <!-- Include footer view -->
</div>
</body>
</html>
<script>
    // Add event listener to the form
    document.querySelector('form').addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent default form submission
        const form = event.target;
        const formData = new FormData(form);
        const url = '../../api/session/register.php'; // API endpoint
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            // Registration successful, redirect to login page
            window.location.href = 'login.php';
        } else {
            // Registration failed, display error message
            alert(result.message);
        }
    });
</script>