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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Use querySelectorAll to apply to multiple forms
            var toggleButtons = document.querySelectorAll('.toggle-button'); // All toggle buttons
            var submitReportButtons = document.querySelectorAll('.report-form button[id^="submit-report-btn"]'); // All submit buttons

            // Loop through toggle buttons to add event listeners
            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    var reportFormId = button.getAttribute('onclick').match(/'([^']+)'/)[1]; // Extract the unique ID
                    var target = document.getElementById(reportFormId);

                    if (target.style.display === "none") {
                        target.style.display = "block";
                    } else {
                        target.style.display = "none";
                    }
                });
            });

            // Loop through submit report buttons to add event listeners
            submitReportButtons.forEach(button => {
                button.addEventListener('click', function () {
                    var type = button.getAttribute('data-type');
                    var id = button.getAttribute('data-id');
                    var reportContent = document.querySelector(`#report-content-${id}`); // Unique textarea based on ID
                    var content = reportContent.value;

                    var formData = new FormData();
                    formData.append('type', type);
                    formData.append('id', id);
                    formData.append('report_content', content);

                    var reportMessage = document.querySelector(`#report-message-${id}`); // Unique report message based on ID

                    fetch('../../../api/submit/submitReport.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            reportMessage.style.display = 'block';
                            reportMessage.innerHTML = data.message;
                        })
                        .catch(error => {
                            reportMessage.style.display = 'block';
                            reportMessage.innerHTML = 'Error: ' + error;
                        });
                });
            });
        });
    </script>
    <style>
        .toggle-button {
            padding: 5px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .toggle-button:hover {
            background-color: #0056b3;
        }

        .report-form {
            display: none; /* Default to hidden */
            flex-direction: column;
            align-items: flex-start;
            padding: 10px;
            border: 2px solid;
            border-radius: 10px;
            background-color: #b6bbc4;
        }

        .report-form label {
            margin-bottom: 10px;
        }

        .report-form textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .report-form button {
            padding: 5px 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .report-form button:hover {
            background-color: #c82333;
        }

        .report-message {
            margin-top: 10px;
            padding: 5px;
            border: 1px solid;
            border-radius: 5px;
            display: none;
        }

    </style>
