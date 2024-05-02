<!-- button/toggleButton.php -->
<?php
function renderCommentButton($targetId, $buttonText) {
    ?>
    <div class="toggle-button-container">
        <button class="toggle-button" onclick="toggleVisibility('<?php echo $targetId; ?>')"><?php echo $buttonText; ?></button>
    </div>
    <script>
        function toggleVisibility(targetId) {
            var target = document.getElementById(targetId);
            if (target.style.display === "none") {
                target.style.display = "block";
            } else {
                target.style.display = "none";
            }
        }
    </script>
    <?php
}
?>

<style>
    /* Style for toggle button */
    .toggle-button-container {
        margin-top: 10px;
    }

    .toggle-button {
        background-color: #0c2d57;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        cursor: pointer;
    }

    .toggle-button:hover {
        background-color: #0056b3;
    }

</style>