function logout() {
    // Send AJAX request to logout.php
    fetch('http://localhost/api/session/logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "login.php";
            } else {
                console.error('Logout failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}