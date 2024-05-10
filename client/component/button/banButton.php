<?php
function renderBanButton($username = null) {
    $usernameValue = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';

    ?>
    <!-- Toggle button to show/hide the ban form -->
    <button class="delete-button" onclick="toggleVisibility('ban-form')">Ban User</button>
    <!-- Unban button -->
    <button class="delete-button" onclick="submitUnbanForm('<?= $usernameValue ?>')">Deban User</button>

    <!-- Form for banning a user with a ban duration -->
    <form id="ban-form" onsubmit="submitBanForm(event)" style="display : none" method="post">
        <!-- Display username as plain text -->
        <p>Username: <?= $usernameValue ?></p>
        <!-- Hidden input field to send username to server -->
        <input type="hidden" name="username" value="<?= $usernameValue ?>" required>
        <!-- Dropdown to select ban duration -->
        <select name="ban_duration" required>
            <option value="" disabled selected>Ban Duration</option>
            <option value="1 day">1 Day</option>
            <option value="7 days">7 Days</option>
            <option value="30 days">30 Days</option>
            <option value="permanent">Permanent</option>
        </select>

        <!-- Textarea for the ban reason -->
        <textarea name="ban_reason" placeholder="Ban Reason" required></textarea>

        <!-- Submit button to send the form via AJAX -->
        <button type="submit">Ban</button>
        <!-- Button to cancel the ban -->
        <button type="button" onclick="toggleVisibility('ban-form')">Cancel</button>
    </form>

    <!-- Styles for the ban button and form -->
    <style>
        .delete-button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-button:hover {
            background-color: #d32f2f;
        }

        #ban-form {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
        }

        input[type=hidden], select {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        button[type=submit]:hover {
            background-color: #45a049;
        }
    </style>

    <script>
        // Function to toggle the visibility of the ban form
        function toggleVisibility(targetId) {
            const target = document.getElementById(targetId);
            if (target.style.display === "none") {
                target.style.display = "block";
            } else {
                target.style.display = "none";
            }
        }

        // Function to handle the form submission with AJAX
        function submitBanForm(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = document.getElementById("ban-form");
            const formData = new FormData(form); // Get form data

            fetch(apiPath + "/update/updateBan.php", {
                method: "POST",
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json(); // Parse the JSON response
                })
                .then(data => {
                    if (data.success) {
                        alert("User successfully banned.");
                        form.reset(); // Reset the form
                        toggleVisibility("ban-form"); // Hide the form
                    } else {
                        console.error("Ban failed:", data);
                        alert(`Failed to ban user: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error("Error submitting ban form:", error);
                    alert("An error occurred while banning the user.");
                });
        }

        // Function to handle the form submission with AJAX
        function submitUnbanForm(username) {
            const formData = new FormData();
            formData.append("username", username);
            formData.append("ban_duration", "no ban");

            fetch(apiPath + "/update/updateBan.php", {
                method: "POST",
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json(); // Parse the JSON response
                })
                .then(data => {
                    if (data.success) {
                        showError(data.message);
                    } else {
                        console.error("Unban failed:", data);
                        showError(`Failed to unban user: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error("Error submitting unban form:", error);
                    showError("An error occurred while unbanning the user.");
                });
        }
    </script>
    <?php
}
