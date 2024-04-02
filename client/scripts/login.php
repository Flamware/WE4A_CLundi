<?php
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password (you should implement more robust validation)
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } else {
        // Make a request to the server-side script
        $url = 'http://localhost/login.php';
        $data = array('action' => 'login', 'username' => $username, 'password' => $password);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        // Create a stream context
        $context = stream_context_create($options);

        // Make the request and get the response
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            // Request failed
            $error = 'Failed to connect to the server';
        } else {
            // Request successful, handle response
            // You can parse the response JSON and perform further actions based on it
            $response = json_decode($result, true);
            if ($response['success']) {
                // Successful login, set cookies and redirect to main page
                setcookie('username', $username, time() + (86400 * 30), "/"); // Set username cookie to expire in 30 days
                header('Location: main.php');
                exit;
            } else {
                // Login failed, display error message
                $error = 'Login failed: ' . $response['message'];
            }
        }
    }
}
?>
<?php
