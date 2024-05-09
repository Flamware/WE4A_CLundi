<?php
/*
 * This is the follow bar component that will be displayed on all pages.
 * It displays the user's account following account.
 */

function displayFollowBar() {
    ?>

    <div class="follow-bar">
        <h2>Following</h2>
            <?php
            $data = null;
            if ($data['success']) {
                foreach ($data['following'] as $username) {
                    ?>
                    <div class="follow-user">
                        <img src="../assets/profile_picture.png" alt="Profile Picture" class="profile-picture" data-author-name="<?= htmlspecialchars($story->author) ?>">
                        <p><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p>Error: Unable to fetch following users.</p>';
            }
            ?>
        </div>
    <?php
}
?>