<?php
/*
 * This is the follow bar component that will be displayed on all pages.
 * It displays the user's account following account.
 */

function displayFollowBar() {
    ?>

    <div class="follow-bar">
        <h2>Following</h2>
            <?php
            $data = null;
            if ($data['success']) {
                foreach ($data['following'] as $username) {
                    ?>
                    <div class="follow-user">
                        <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?php echo $message['is_sender'] ? $messageAuthor : $messageRecipient; ?>">
                        <p><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p>Error: Unable to fetch following users.</p>';
            }
            ?>
        </div>


<script>
    // Fetch the following list from the server
    function fetchFollowing() {
        fetch('<?php echo API_PATH; ?>/load/loadFollowing.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    displayFollowing(data.following);
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => {
                console.error(error);
            });
    }

    function displayFollowing(following) {
        const followContainer = document.querySelector('.follow-container');
        followContainer.innerHTML = ''; // Clear the container

        following.forEach(username => {
            const followUser = document.createElement('div');
            followUser.classList.add('follow-user');
            followUser.innerHTML = `
                <img src="<?php echo API_PATH; ?>/load/loadProfilePicture.php?username=${username}" alt="${username} profile picture">
                <p>${username}</p>
            `;
            followContainer.appendChild(followUser);
        });
    }

    document.addEventListener('DOMContentLoaded', fetchFollowing);
</script>

<style>
    .follow-bar {
        margin: auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .follow-bar h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }


    .follow-user {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;  /* Less margin between users */
    }

    .follow-user img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .follow-user p {
        margin: 0;
        font-size: 1rem;
        color: #333;  /* Standard text color */
    }

</style>
    <?php
}
?>