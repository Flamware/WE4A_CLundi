<!-- CreateAccountView.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte</title>
    <style>
        /* Your CSS styles for create account page here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #8D86C9;
            color: #333;
        }

        main {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 20px;
            padding: 20px;
            background-color: #b6bbc4;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            color: black;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        form {
            margin-top: 10px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            color: black;
        }

        button {
            background-color: #0c2d57;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #555;
        }

        #error-message {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
<div>
    <?php include '../component/header.php'; ?> <!-- Include header view -->
    <main>
        <section id="create-account-form">
            <h2>Créer un Compte</h2>
            <form action="register.php" method="POST"> <!-- Your PHP script for handling account creation -->
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Confirmez le mot de passe :</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit" id="create-account-button">Créer un Compte</button>
            </form>

            <p>Déjà un compte ? <a href="login.php">Connexion</a></p>
        </section>
    </main>
    <?php include '../component/footer.php'; ?> <!-- Include footer view -->
</div>
</body>
</html>
