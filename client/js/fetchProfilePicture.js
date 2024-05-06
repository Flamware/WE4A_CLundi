function loadProfilePicture(profilePictureElement) {
    var authorName = profilePictureElement.getAttribute('data-author-name');
    var profilePicturePath = localStorage.getItem('profile_picture_' + authorName);
    // Check if profile picture is already stored in localStorage
    if (profilePicturePath) {
        // If found, use it directly
        profilePictureElement.setAttribute('src', profilePicturePath);
    } else {
        // If not found, make an AJAX request to fetch it
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText); // Parse the JSON response
                var profilePicturePath;
                if (response.profile_picture === '') {
                    profilePicturePath = "../assets/profile_picture.png"; // Default
                } else {
                    profilePicturePath = "http://localhost/api/uploads/profile_picture/" + response.profile_picture;
                }
                profilePictureElement.setAttribute('src', profilePicturePath);
                localStorage.setItem('profile_picture_' + authorName, profilePicturePath);
            }
        };

        // Send the AJAX request to fetch the profile picture
        xhr.open('GET', 'http://localhost/api/load/loadProfilePicture.php?author=' + authorName, true);
        xhr.send();
    }
}

// Load profile pictures when document is ready
document.addEventListener('DOMContentLoaded', function() {
    var profilePictures = document.querySelectorAll('.profile-picture');
    profilePictures.forEach(function(profilePicture) {
        loadProfilePicture(profilePicture);
    });
});
