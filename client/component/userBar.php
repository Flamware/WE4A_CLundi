<?php
function displayUserBar() {
    ?>
    <div id="user-container">
        <h>Users of Clundi :</h>
    </div>
    <div id="button-container">
        <button id="show-more-button">Show More</button>
        <button id="show-less-button" style="display: none;">Show Less</button> <!-- Initially hidden -->
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Variables to track the current page and limit
            let currentPage = 1;
            const usersPerPage = 3; // Change this to your desired number of users per page

            // Function to fetch users from the server
            function fetchUsers() {
                fetch('../../api/load/loadUsers.php?page=' + currentPage)
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'users' property of the data object
                        const users = data.users;

                        // Append fetched users to the user container
                        const userContainer = document.getElementById('user-container');
                        users.forEach(user => {
                            const userElement = document.createElement('div');
                            userElement.classList.add('user'); // Add 'user' class

                            // Add profile picture image
                            const imgElement = document.createElement('img');
                            imgElement.src = 'http://localhost/api/uploads/profile_picture/default_profile_picture.jpg'; // Adjust image source
                            imgElement.alt = 'Profile Picture';
                            imgElement.classList.add('profile-picture');
                            imgElement.setAttribute('data-author-name', user.username); // Adjust data attribute
                            userElement.appendChild(imgElement); // Append image to user element
                            // Add username text
                            const usernameElement = document.createElement('span');
                            usernameElement.textContent = user.username; // Adjust according to your user data
                            userElement.appendChild(usernameElement); // Append username to user element
                            loadProfilePicture(imgElement);
                            userContainer.appendChild(userElement); // Append user element to user container
                        });

                        currentPage++; // Increment page for the next request

                        // Hide the "Show More" button if there are no more users to load
                        if (users.length < usersPerPage) {
                            document.getElementById('show-more-button').style.display = 'none';
                        } else {
                            document.getElementById('show-more-button').style.display = 'inline-block';
                        }

                        // Display the "Show Less" button if there are more than one page of users
                        if (currentPage > 2) {
                            document.getElementById('show-less-button').style.display = 'inline-block';
                        }
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }

            // Event listener for "Show More" button click
            document.getElementById('show-more-button').addEventListener('click', fetchUsers);

            // Event listener for "Show Less" button click
            document.getElementById('show-less-button').addEventListener('click', function() {
                // Clear the user container
                document.getElementById('user-container').innerHTML = '';

                // Reset currentPage to 1
                currentPage = 1;

                // Fetch users again
                fetchUsers();

                // Hide the "Show Less" button
                document.getElementById('show-less-button').style.display = 'none';
            });

            // Initial load of users
            fetchUsers();
        });
    </script>


    <style>
        #user-container {
            margin-top: 20px;
            padding: 20px;
        }

        #user-container h {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .user {
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Align items vertically */
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            border: 2px solid black; /* Add black border */

        }

        .user span {
            flex-grow: 1; /* Allow username to take up remaining space */
        }

        #button-container {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            margin-top: 20px;
        }

        #show-more-button,
        #show-less-button {
            margin-left: 10px; /* Add some spacing between buttons */
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #0c2d57;
            cursor: pointer;
            color: white;
            border: none;
        }
        .profile-picture {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 10px;
        }

    </style>
    <?php
}
?>
