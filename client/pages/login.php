<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css"> <!-- Link to styles.css -->
    <script>
        // Function to store session user locally
        // Function to store session user locally
        function storeSessionUser(username) {
            localStorage.setItem('sessionUser', username);
        }

        // Function to store received cookie
        function storeCookie(cookie) {
            localStorage.setItem('authCookie', cookie);
        }

        // Function to retrieve stored cookie
        function getStoredCookie() {
            return localStorage.getItem('authCookie');
        }

        // Function to handle server response after form submission
        function handleResponse(response) {
            if (response.success) {
                var username = response.username; // Retrieve username from server response
                storeSessionUser(username); // Store username in local storage
                storeCookie(response.cookie); // Store received cookie
                // Display welcome message
                document.getElementById('welcome-message').innerText = 'Welcome, ' + username + '!';
                //redirect to main page
                window.location.href = 'main.php';
            } else {
                document.getElementById('error-message').innerText = response.message;
            }
        }


        // Function to submit form asynchronously
        function submitForm(event) {
            event.preventDefault(); // Prevent default form submission
            var form = document.getElementById('login-form');
            var formData = new FormData(form);

            fetch('http://localhost/login.php', {
                method: 'POST',
                body: formData,
                credentials: 'include'
            })
                .then(response => response.json())
                .then(data => handleResponse(data))
                .catch(error => console.error('Error:', error));
        }

        // Function to pre-fill username if session exists
        window.onload = function() {
            var sessionUser = localStorage.getItem('sessionUser');
            if (sessionUser) {
                document.getElementById('username').value = sessionUser;
            }
        };

    </script>

</head>
<body>
<div class="login">
    <?php include '../component/header.php'; ?>

    <main>
        <section id="login-form-section">
            <h2>Connexion</h2>
            <div id="error-message" class="error-message"></div>
            <!-- Error message container -->

            <form id="login-form" onsubmit="submitForm(event)">
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

            <!-- Display welcome message -->
            <div id="welcome-message"></div>
        </section>
    </main>

    <?php include '../component/footer.php'; ?> <!-- Include footer view -->
</div>

<script>
    // Function to pre-fill username if session exists
    window.onload = function() {
        var sessionUser = localStorage.getItem('sessionUser');
        if (sessionUser) {
            document.getElementById('username').value = sessionUser;
        }
    };
</script>

</body>
</html>
