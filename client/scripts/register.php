<?php
if($_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Retrieve username, email, and password from the POST data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    // post request to the server
    $url = 'http://localhost/register.php';
    $data = array('action' => 'register', 'username' => $username, 'email' => $email, 'password' => $password, 'confirm-password' => $confirmPassword);
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
        // Create a stream context
        $context = stream_context_create($options);
        // Make the request and get the response
        $result = file_get_contents($url, false, $context);
        //if the request failed show an error message
        if ($result === FALSE) {
            $error = 'Echec de la création de votre compte, verifiez votre connexion internet et réessayez.';
            echo $error;
        } else {
            // Request successful, handle response
            // You can parse the response JSON and perform further actions based on it
            $response = json_decode($result, true);
            if ($response['success']) {
               // Successful registration, redirect to login page
                echo 'Votre compte a été créé avec succès, veuillez vous connecter.';
            } else {
                // Register failed, display error message
                $error = 'Register failed: ' . $response['message'];
                echo $error;
            }
        }
}
