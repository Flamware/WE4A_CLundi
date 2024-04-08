function showError(message) {
    // Create error container
    var errorContainer = document.createElement('div');
    errorContainer.id = 'error-container';
    errorContainer.classList.add('error-container');

    // Create error message
    var errorMessage = document.createElement('div');
    errorMessage.id = 'error-message';
    errorMessage.classList.add('error-message');
    errorMessage.textContent = message;

    // Create close button
    var closeButton = document.createElement('button');
    closeButton.id = 'close-error-btn';
    closeButton.classList.add('close-error-btn');
    closeButton.innerHTML = '&times;';

    // Append elements to error container
    errorContainer.appendChild(errorMessage);
    errorContainer.appendChild(closeButton);

    // Append error container to login form section
    var loginFormSection = document.getElementById('error-message');
    loginFormSection.appendChild(errorContainer);

    // Add event listener to close button
    closeButton.addEventListener('click', function () {
        errorContainer.remove();
    });

}