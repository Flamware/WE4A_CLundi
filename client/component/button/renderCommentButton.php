<?php
function renderCommentButton($targetId, $buttonText) {
    ?>
    <div class="toggle-button-container">
        <!-- Ensure the button has a unique event -->
        <button class="toggle-button" onclick="toggleVisibility('<?php echo $targetId; ?>')"><?php echo htmlentities($buttonText); ?></button>
    </div>
    <?php
}
?>