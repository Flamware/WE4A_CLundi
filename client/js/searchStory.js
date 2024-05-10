document.addEventListener("DOMContentLoaded", function () {
    const storySearchButton = document.getElementById("story-search-button");

    // Function to redirect to the search page with a query parameter
    function searchStories() {
        const query = document.querySelector(".story-search-input").value.trim();

        if (query === "") {
            alert("Please enter a search query");
            return;
        }

        window.location.href = `main.php?search=${encodeURIComponent(query)}`; // Redirect with query parameter
    }

    storySearchButton.addEventListener("click", searchStories); // Event listener for the search button

    const inputs = document.querySelectorAll(".story-search-input");
    const suggestionContainers = document.querySelectorAll(".story-search-container");

    // Debounce function to avoid excessive AJAX calls during rapid input changes
    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    inputs.forEach((input, index) => {
        const suggestionsContainer = suggestionContainers[index];

        // Code for handling input event and fetching suggestions
        input.addEventListener(
            "input",
            debounce(function () {
                const query = input.value.trim();

                if (query === "") {
                    suggestionsContainer.innerHTML = ""; // Clear suggestions if no input
                    return;
                }

                // AJAX request to fetch story suggestions
                const xhr = new XMLHttpRequest();
                xhr.open("GET", apiPath + `/load/loadStories.php?query=${encodeURIComponent(query)}`);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);

                        // Clear previous suggestions before appending new ones
                        suggestionsContainer.innerHTML = "";

                        if (response.success && response.stories.length > 0) {
                            // Iterate over each story in the response
                            response.stories.forEach((story) => {
                                const suggestionDiv = document.createElement("div");
                                suggestionDiv.classList.add("story");

                                // Story header
                                const storyHeader = document.createElement("div");
                                storyHeader.classList.add("story-header");

                                const authorLink = document.createElement("a");
                                authorLink.href = `../pages/wall.php?username=${encodeURIComponent(story.author)}`;

                                const profilePicture = document.createElement("img");
                                profilePicture.src = "../assets/profile_picture.png"; // Profile picture source
                                profilePicture.alt = "Profile Picture";
                                profilePicture.classList.add("profile-picture");
                                profilePicture.setAttribute("data-author-name", story.author);
                                loadProfilePicture(profilePicture);

                                const authorName = document.createElement("span");
                                authorName.classList.add("author-name");
                                authorName.textContent = story.author;

                                authorLink.appendChild(profilePicture);
                                storyHeader.appendChild(authorLink);
                                storyHeader.appendChild(authorName);

                                // Story content
                                const storyContent = document.createElement("div");
                                storyContent.classList.add("story-content");

                                const content = document.createElement("p");
                                content.classList.add("content");
                                content.textContent = story.content;

                                storyContent.appendChild(content);

                                if (story.story_image) {
                                    const storyImage = document.createElement("img");
                                    storyImage.src = apiPath + `/uploads/stories/${story.story_image}`;
                                    storyImage.alt = "Story Image";
                                    storyImage.classList.add("story-image");
                                    storyContent.appendChild(storyImage);
                                }

                                const date = document.createElement("span");
                                date.classList.add("story-date");
                                date.textContent = `Date: ${story.date}`;

                                suggestionDiv.appendChild(storyHeader);
                                suggestionDiv.appendChild(storyContent);
                                suggestionDiv.appendChild(date);

                                const suggestionButton = document.createElement("button");
                                suggestionButton.classList.add("story-search-button");
                                suggestionButton.textContent = "View Story";
                                suggestionButton.addEventListener("click", function () {
                                    window.location.href = `main.php?search=${story.content}`; // Redirect to story
                                });

                                suggestionDiv.appendChild(suggestionButton); // Append the button to suggestionDiv
                                suggestionsContainer.appendChild(suggestionDiv); // Add suggestionDiv to suggestionsContainer

                            });
                        } else {
                            suggestionsContainer.textContent = "No suggestions found"; // When there are no matching stories
                        }
                    } else {
                        console.error("Request failed:", xhr.status, xhr.statusText);
                    }
                };

                xhr.send(); // Send the AJAX request
            }, 300) // Debounce delay
        );
    });
});
