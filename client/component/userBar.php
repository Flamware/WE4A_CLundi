<?php
function displayUserBar($url) {
    ?>
        <div id="search-container">
            <input type="text" class="fetched-user" placeholder="Search user..." />
            <div class="suggestions-container"></div>
            <button id="search-button">Search</button>
        </div>
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

                            // Create an anchor element for the user
                            const userLink = document.createElement('a');
                            userLink.href = '<?php echo $url; ?>' + user.username; // Link to the specified URL with username parameter

                            // Add profile picture image
                            const imgElement = document.createElement('img');
                            imgElement.src = 'http://localhost/api/uploads/profile_picture/default_profile_picture.jpg'; // Adjust image source
                            imgElement.alt = 'Profile Picture';
                            imgElement.classList.add('profile-picture');
                            imgElement.setAttribute('data-author-name', user.username); // Adjust data attribute
                            userLink.appendChild(imgElement); // Append image to user link

                            // Add username text
                            const usernameElement = document.createElement('span');
                            usernameElement.textContent = user.username; // Adjust according to your user data
                            userLink.appendChild(usernameElement); // Append username to user link
                            loadProfilePicture(imgElement);

                            userElement.appendChild(userLink); // Append user link to user element
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
        // when click on search button get req to the url with the username as parameter
        document.getElementById('search-button').addEventListener('click', function() {
            const username = document.querySelector('.fetched-user').value;
            window.location.href = '<?php echo $url; ?>' + username;
        });
    </script>


    <style>
        #search-container {
            margin-bottom: 20px;
            padding: 10px;
        }

        #user-container {
            padding: 20px;
        }

        #user-container h {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .user {
            display: flex; /* Flex layout to align items */
            align-items: center; /* Vertical alignment */
            padding: 10px; /* Padding for spacing */
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            background-color: #f9f9f9; /* Light background */
            margin-bottom: 10px; /* Spacing between user elements */
            transition: background-color 0.3s; /* Smooth transition for hover effects */
        }

        .user:hover {
            background-color: #e0e0e0; /* Change background color on hover */
        }

        .user a {
            text-decoration: none; /* Remove underline from links */
            color: #333; /* Default text color */
            flex-grow: 1; /* Allow link to grow to fill space */
        }

        .user a:hover {
            text-decoration: underline; /* Add underline on hover */
        }

        .user .profile-picture {
            width: 40px; /* Increased size for better visibility */
            height: 40px;
            border-radius: 50%; /* Circular profile picture */
            object-fit: cover; /* Maintain aspect ratio */
            margin-right: 10px; /* Space between image and text */
        }

        #button-container {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            margin-top: 20px;
        }

        #show-more-button,
        #show-less-button {
            margin-left: 10px; /* Add spacing between buttons */
            padding: 10px 20px; /* Padding for buttons */
            border-radius: 5px; /* Rounded corners */
            background-color: #0c2d57; /* Darker blue for buttons */
            color: white; /* White text for contrast */
            border: none; /* No border */
            cursor: pointer; /* Change cursor to indicate clickable */
            transition: background-color 0.3s; /* Smooth transition for hover effects */
        }

        #show-more-button:hover,
        #show-less-button:hover {
            background-color: #07335f; /* Darker on hover */
        }

        /* Style for suggestion component */
        .suggestion-container {
            max-height: 150px; /* Scrollable height */
            overflow-y: auto; /* Enable vertical scrolling */
            background-color: #f0f0f0; /* Background color for suggestion area */
            border: 1px solid #ccc; /* Border around suggestions */
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Padding for spacing */
        }

        .suggestion {
            display: flex; /* Use flex layout for alignment */
            align-items: center; /* Vertical alignment */
            padding: 5px; /* Spacing within each suggestion */
            border-bottom: 1px solid #ccc; /* Separator between suggestions */
        }

        .suggestion:last-child {
            border-bottom: none; /* Remove border from the last suggestion */
        }

        .suggestion .suggestion-button {
            background: none; /* No background for buttons */
            border: none; /* No border */
            text-align: left; /* Align text to the left */
            padding: 5px; /* Padding for spacing */
            cursor: pointer; /* Indicate clickable */
        }

        .suggestion .suggestion-button:hover {
            background-color: #e0e0e0; /* Change background color on hover */
        }

        .suggestion .profile-picture {
            width: 30px; /* Profile picture size */
            height: 30px;
            border-radius: 50%; /* Circular shape */
            margin-right: 10px; /* Spacing between profile picture and text */
        }

    </style>
    <?php
}
?>
