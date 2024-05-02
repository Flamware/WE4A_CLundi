<?php
function displayReportForm($type, $id){
    $reportFormId = 'report-form-' . $id; // Generate unique ID for report form section
    ?>
    <button class="toggle-button" onclick="toggleVisibility('<?php echo $reportFormId; ?>')">Toggle Report Form</button>
    <section id="<?php echo $reportFormId; ?>" style="display: none;"> <!-- Use the unique ID here -->
        <label for="report-content">Raison du signalement :</label>
        <textarea id="report-content" rows="4" required placeholder="Entrez votre raison ici"></textarea>
        <button id="submit-report-btn" data-type="<?php echo $type; ?>" data-id="<?php echo $id; ?>">Signaler</button>
        <div id="report-message"></div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var submitReportBtn = document.querySelector('#<?php echo $reportFormId; ?> #submit-report-btn'); // Query inside the specific report form
            var reportContent = document.querySelector('#<?php echo $reportFormId; ?> #report-content');
            var reportMessage = document.querySelector('#<?php echo $reportFormId; ?> #report-message');

            submitReportBtn.addEventListener('click', function () {
                var type = this.getAttribute('data-type');
                var id = this.getAttribute('data-id');
                var content = reportContent.value;

                var formData = new FormData();
                formData.append('type', type);
                formData.append('id', id);
                formData.append('report_content', content);

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

        function toggleVisibility(targetId) {
            var target = document.getElementById(targetId);
            if (target.style.display === "none") {
                target.style.display = "block";
            } else {
                target.style.display = "none";
            }
        }
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

        #report-form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 10px;
            border: 2px solid;
            border-radius: 10px;
            background-color: #b6bbc4;
        }

        #report-form label {
            margin-bottom: 10px;
        }

        #report-form textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #report-form button {
            padding: 5px 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #report-form button:hover {
            background-color: #c82333;
        }

        #report-message {
            margin-top: 10px;
            padding: 5px;
            border: 1px solid;
            border-radius: 5px;
            display: none;
        }
    </style>
    <?php
}
?>
