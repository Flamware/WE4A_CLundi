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
                fetch('<?php echo API_PATH ?>/load/loadUsers.php?page=' + currentPage)
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
                            imgElement.src = '../assets/profile_picture.png'; // Adjust image source
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
    <?php
}
?>
