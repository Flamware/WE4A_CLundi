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
            xhr.open("GET", apiPath + `/suggestion.php?query=${query}`);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const users = JSON.parse(xhr.responseText);

                    const userFetched = document.createElement("div");
                    userFetched.classList.add("user-fetched"); // For scrollability

                    users.forEach((user) => {
                        const suggestionDiv = document.createElement("div");
                        suggestionDiv.classList.add("user");

                        const profilePicture = document.createElement("img");
                        profilePicture.alt = "Profile Picture";
                        profilePicture.classList.add("profile-picture");
                        profilePicture.setAttribute("data-author-name", user.username);

                        loadProfilePicture(profilePicture, user.username); // Fetch and set profile picture

                        const suggestionButton = document.createElement("button");
                        suggestionButton.classList.add("user-button");
                        suggestionButton.textContent = user.username;

                        suggestionButton.addEventListener("click", function () {
                            receiverInput.value = user.username; // Set the input field
                            suggestionsContainer.innerHTML = ""; // Clear suggestions
                        });

                        suggestionDiv.appendChild(profilePicture); // Add profile picture to suggestion div
                        suggestionDiv.appendChild(suggestionButton); // Add button to suggestion div

                        userFetched.appendChild(suggestionDiv); // Add suggestion div to userFetched
                    });

                    suggestionsContainer.innerHTML = ""; // Clear previous suggestions
                    suggestionsContainer.appendChild(userFetched); // Add new suggestions
                } else {
                    console.error("Request failed:", xhr.status);
                }
            };

            xhr.send(); // Send the AJAX request
        });
    });
});
