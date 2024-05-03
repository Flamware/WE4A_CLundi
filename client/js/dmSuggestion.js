document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".fetched-user"); // Get all elements with the class name
    const suggestionContainers = document.querySelectorAll(".suggestions-container"); // Get corresponding suggestion containers

    inputs.forEach((receiverInput, index) => {
        const suggestionsContainer = suggestionContainers[index]; // Match with corresponding container

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
                    const suggestionContainer = document.createElement("div");
                    suggestionContainer.classList.add("suggestion-container"); // For scrollability

                    users.forEach((user) => {
                        const profilePicture = document.createElement("img");
                        profilePicture.alt = "Profile Picture";
                        profilePicture.classList.add("profile-picture");

                        loadProfilePicture(profilePicture, user.username); // Fetch and set profile picture

                        const suggestionDiv = document.createElement("div");
                        suggestionDiv.classList.add("suggestion");

                        const suggestionButton = document.createElement("button");
                        suggestionButton.classList.add("suggestion-button");
                        suggestionButton.textContent = user.username;

                        suggestionButton.addEventListener("click", function () {
                            receiverInput.value = user.username; // Set the input field
                            suggestionsContainer.innerHTML = ""; // Clear suggestions
                        });

                        suggestionDiv.appendChild(profilePicture); // Add profile picture to suggestion div
                        suggestionDiv.appendChild(suggestionButton); // Add button to suggestion div
                        suggestionContainer.appendChild(suggestionDiv); // Add div to container
                    });

                    suggestionsContainer.innerHTML = ""; // Clear previous suggestions
                    suggestionsContainer.appendChild(suggestionContainer); // Add new suggestions
                } else {
                    console.error("Request failed:", xhr.status);
                }
            };

            xhr.send(); // Send the AJAX request
        });
    });
});
