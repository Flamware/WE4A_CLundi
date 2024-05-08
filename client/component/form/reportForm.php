<?php
function displayReportForm($type, $id) {
    $reportFormId = 'report-form-' . $id; // Generate unique ID for the report form section
    ?>
    <button class="toggle-button" onclick="toggleVisibility('<?php echo $reportFormId; ?>')">Toggle Report Form</button>
    <section id="<?php echo $reportFormId; ?>" class="report-form"> <!-- Common class for CSS -->
        <label for="report-content-<?php echo $id; ?>">Raison du signalement :</label> <!-- Ensure unique "for" attribute -->
        <textarea id="report-content-<?php echo $id; ?>" rows="4" required placeholder="Entrez votre raison ici"></textarea> <!-- Unique ID for input elements -->
        <button id="submit-report-btn-<?php echo $id; ?>" data-type="<?php echo $type; ?>" data-id="<?php echo $id; ?>">Signaler</button> <!-- Ensure unique IDs -->
        <div id="report-message-<?php echo $id; ?>"></div> <!-- Ensure unique IDs -->
    </section>
    <?php
}
?>