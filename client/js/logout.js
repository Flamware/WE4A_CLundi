function logout() {
    //clear the session
    sessionStorage.clear();
    localStorage.clear();
    // Send AJAX request to logout.php
    fetch(apiPath + '/session/logout.php')
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