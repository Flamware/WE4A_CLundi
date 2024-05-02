document.addEventListener("DOMContentLoaded", function () {
    const receiverInput = document.getElementById("receiver");
    const suggestionsContainer = document.getElementById("suggestions-container");

    receiverInput.addEventListener("input", function () {
        const query = receiverInput.value.trim(); // Get the value of the input field

        if (query === "") {
            suggestionsContainer.innerHTML = ""; // Clear suggestions if input is empty
            return;
        }

        // AJAX request to fetch suggestions
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `http://localhost/api/suggestion.php?query=${query}`);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const users = JSON.parse(xhr.responseText);
                // Create a new suggestion container for scrollability
                const suggestionContainer = document.createElement("div");
                suggestionContainer.classList.add("suggestion-container"); // Apply CSS for scrollability

                users.forEach((user) => {
                    const profilePicture = document.createElement('img');
                    profilePicture.alt = 'Profile Picture';
                    profilePicture.classList.add('profile-picture');
                    profilePicture.setAttribute('data-author-name', user.username);

                    // Fetch and set the profile picture
                    loadProfilePicture(profilePicture, user.username);

                    const suggestionDiv = document.createElement("div");
                    suggestionDiv.classList.add("suggestion");

                    const suggestionButton = document.createElement("button");
                    suggestionButton.classList.add("suggestion-button");
                    suggestionButton.textContent = user.username;

                    suggestionButton.addEventListener("click", function () {
                        receiverInput.value = user.username; // Set the receiver input
                        suggestionsContainer.innerHTML = ""; // Clear suggestions
                    });
                    suggestionDiv.appendChild(profilePicture); // Add profile picture to suggestion div
                    suggestionDiv.appendChild(suggestionButton); // Add to suggestion div
                    suggestionContainer.appendChild(suggestionDiv); // Add to scrollable container
                });

                suggestionsContainer.innerHTML = ""; // Clear previous suggestions
                suggestionsContainer.appendChild(suggestionContainer); // Add the scrollable container
            } else {
                console.error("Request failed:", xhr.status);
            }
        };
        xhr.send(); // Send the AJAX request
    });
});
