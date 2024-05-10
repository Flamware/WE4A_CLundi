// Fetch the following list from the server
function fetchFollowing() {
    var url = apiPath + '/load/loadFollowing.php';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.following.length === 0) {
                    return;
                }
                displayFollowing(data.following);
            } else {
                console.error('Fetch failed:', data.message);
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while fetching the following list. Please try again.');
        });
}

function displayFollowing(following) {
    const followContainer = document.querySelector('.follow-container');
    followContainer.innerHTML = ''; // Clear the container

    following.forEach(username => {
        const followUser = document.createElement('div');
        followUser.classList.add('follow-user');
        followUser.innerHTML = `
                <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="${username}">
                <p>${username}</p>
                <button class="unfollow-button" onclick="unfollowUser('${username}')">Unfollow</button>
            `;
        followContainer.appendChild(followUser);
        loadProfilePicture(followUser.querySelector('.profile-picture'), username);
    });
}

function unfollowUser(username) {
    var formData = new FormData();
    formData.append('username', username);

    var url = apiPath + '/delete/following.php';
    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchFollowing();
            } else {
                console.error('Unfollow failed:', data.message);
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while unfollowing the user. Please try again.');
        });
}

document.addEventListener('DOMContentLoaded', fetchFollowing);