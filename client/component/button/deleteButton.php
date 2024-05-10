<?php
function renderDeleteButton($id, $isStory)
{
    ?>
    <form class="deleteForm" action="<?php echo API_PATH ?>/delete/<?php echo $isStory ? 'story' : 'comment'; ?>.php" method="post">
        <input type="hidden" name="<?php echo $isStory ? 'story_id' : 'comment_id'; ?>" value="<?php echo $id; ?>">
        <button type="button" class="delete-button" onclick="submitDeleteForm(this)">Supprimer</button>
    </form>
    <?php
}
?>