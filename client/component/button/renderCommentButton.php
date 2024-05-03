<?php
function renderCommentButton($targetId, $buttonText) {
    ?>
    <div class="toggle-button-container">
        <!-- Ensure the button has a unique event -->
        <button class="toggle-button" onclick="toggleVisibility('<?php echo $targetId; ?>')"><?php echo htmlentities($buttonText); ?></button>
    </div>

    <!-- Ensure scripts are placed at the end of the body -->
    <script>
            function toggleVisibility(targetId) {
                const target = document.getElementById(targetId);

                if (target) {
                    console.log("Attempting to toggle visibility for ID:", targetId);
                    // Toggle visibility
                    if (target.style.display === 'hidden') {
                        target.style.display = 'visible';
                    } else {
                        target.style.display = 'hidden';
                    }
                }
            }
    </script>



<style>
    /* Style for the toggle button */
    .toggle-button-container {
        margin-top: 10px; /* Spacing between the button and other elements */
    }

    .toggle-button {
        background-color: #0c2d57; /* Dark blue background */
        color: #fff; /* White text color */
        border: none; /* No border */
        border-radius: 5px; /* Rounded corners */
        padding: 8px 16px; /* Padding for the button */
        cursor: pointer; /* Change cursor on hover */
        transition: background-color 0.3s; /* Smooth transition */
    }

    .toggle-button:hover {
        background-color: #0056b3; /* Change background color on hover */
    }
</style>

    <?php
}
?>