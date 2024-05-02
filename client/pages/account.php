<?php
/*
 * This file contains the HTML and PHP code for displaying the user's account information and providing options for account management.
 * It includes a form for updating the user's profile information, such as username, email, and profile picture.
 * You can customize this page to include additional account management options, such as password change or account deletion.
 * source: https://github.com/Flamware/CLundi
 */
session_start();
include '../component/navbar.php'; // Include the navbar component
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/error.js"></script>
    <script src="../js/fetchProfilePicture.js"></script>
    <link rel="stylesheet" href="../css/error.css">
    <title>Account</title>
    <?php include '../component/header.php'; ?>
</head>
<body>
<?php displayNavBar(); ?>
<!-- Error message container -->
<div id="error-message" class="error-message"></div>
<div class="container">
    <h2>Account Information</h2>
    <div class="info">
        <div class="profile-picture">
            <img id="profile-pic" class="profile-pic" src="http://localhost/api/profile_picture/default_profile_picture.jpg" alt="Profile Picture" data-author-name="<?php echo $_SESSION['username']; ?>">
                <form id="update-profile-picture-form" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" id="profile_picture" name="profile_picture">
                    </div>
                    <div class="button-container">
                        <button type="submit">Update Picture</button>
                    </div>
                </form>
        </div>
            <div class="account-details">
            <p><strong>Username:</strong> </p>
            <p><strong>Email:</strong> </p>
            <p><strong>First Name:</strong></p>
            <p><strong>Last Name:</strong></p>
        </div>
    </div>
    <h2>Account Management</h2>
    <div class="update-profile">
        <h3>Update Profile Information</h3>
        <form id="update-form" method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="button-container">
                <button type="submit">Update</button>
            </div>
        </form>


</div>
<?php include '../component/footer.php'; ?> <!-- Include footer view -->
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        loadAccountInfo();

        function loadAccountInfo() {
            // Fetch user account information from the server
            fetch('http://localhost/api/account_info.php')
                .then(response => response.json())
                .then(data => {
                    // Update the account information fields with the fetched data
                    document.querySelector('.info p:nth-child(1)').innerHTML = "<strong>Username:</strong> " + data.user.username;
                    document.querySelector('.info p:nth-child(2)').innerHTML = "<strong>Email:</strong> " + data.user.email;
                    document.querySelector('.info p:nth-child(3)').innerHTML = "<strong>First Name:</strong> " + data.user.first_name;
                    document.querySelector('.info p:nth-child(4)').innerHTML = "<strong>Last Name:</strong> " + data.user.last_name;
                    // Update the profile picture
                    document.getElementById('profile-pic').src = "http://localhost/api/uploads/profile_picture/" + data.user.profile_picture;
                    // put in local storage the profile picture
                    const authorName = document.getElementById('profile-pic').getAttribute('data-author-name');
                    const profilePicturePath = data.user.profile_picture;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching account information. Please try again.');
                });
        }

        // Add event listener for form submission
        document.getElementById('update-form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission

            // Serialize form data
            const formData = new FormData(this);

            // Send POST request to update account information
            fetch('../../api/update/update_account.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showError(data.message);
                        // Clear input fields
                        document.getElementById('username').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('first_name').value = '';
                        document.getElementById('last_name').value = '';
                        //wait for 1 second before reloading the page
                        setTimeout(function () {
                            loadAccountInfo();
                        }, 1000);
                    } else {
                        showError(data.message);
                        alert('Failed to update account information. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating account information. Please try again.');
                });
        });
        document.getElementById('update-profile-picture-form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission

            // Serialize form data
            const formData = new FormData(this);

            // Send POST request to update profile picture
            fetch('../../api/update/update_profile_picture.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showError(data.message);
                        // Reload profile picture
                        loadAccountInfo();
                    } else {
                        showError(data.message);
                        alert('Failed to update profile picture. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating profile picture. Please try again.');
                });
        });
    });

</script>

<style>

    body {
        font-family: Arial, sans-serif;
        background: url("../assets/background.jpg");
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    p {
        margin-bottom: 10px;
    }

    .info {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
    }

    .account-details {
        overflow: hidden; /* Clear float */
    }

    .profile-pic {
        float: left;
        margin-right: 20px;
        width: 150px;
        height: 150px;
        border-radius: 50%; /* Make it a circle */
        object-fit: cover; /* Maintain aspect ratio */
        object-position: center; /* Center the image */
        display: block;
    }


    .update-profile {
        margin-bottom: 30px;
    }

    .update-profile h3 {
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"] {
        width: calc(100% - 12px); /* Adjusted to account for input padding and border */
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .button-container {
        display: flex;
        justify-content: flex-end; /* Align buttons to the right */
        margin-top: 20px; /* Added margin to separate buttons from form inputs */
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: #fff;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

</style>
