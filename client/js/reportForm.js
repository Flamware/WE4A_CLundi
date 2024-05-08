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