<?php
$url = 'http://localhost:8080/api/v1/connexion';
$username = $_POST['username'];
$password = $_POST['password'];
$data = array('username' => $username, 'password' => $password);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

?>

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
            <?php if (!empty($error)) { ?>
                <div id="error-message"><?php echo $error; ?></div>
            <?php } ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="button-container">
                    <button type="submit">Se connecter</button>
                    <button type="button" onclick="window.location.href='signup.php'">S'inscrire</button> <!-- Link to signup page -->
                </div>
            </form>
        </section>
    </main>
    <?php include '../component/footer.php'; ?> <!-- Include footer view -->
</div>
</body>
</html>
