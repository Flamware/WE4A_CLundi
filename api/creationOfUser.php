<?php

$url = 'http://localhost/api/session/register.php';

// User data to be posted
$user_data = array(
    'username' => 'user',  // You may adjust this based on your username pattern
    'email' => 'user{}@example.com',  // You may adjust this based on your email pattern
    'password' => 'password',
    'confirm-password' => 'password'
);

// Send POST requests to create 100 users
for ($i = 1; $i <= 100; $i++) {
    // Adjust username and email based on your desired pattern
    $user_data['username'] = 'user' . $i;
    $user_data['email'] = 'user' . $i . '@example.com';

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options for POST request
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($user_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    // Check if user creation was successful
    if ($result['success']) {
        echo "User $i created successfully\n";
    } else {
        echo "Error creating user $i: " . $result['message'] . "\n";
    }
}

?>
