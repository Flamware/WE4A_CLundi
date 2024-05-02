
function renderContent(route) {
    // Fetch the content of the route using AJAX or fetch API
    fetch(route)
        .then(response => {
            // Check if the response is successful
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // Return the response text
            return response.text();
        })
        .then(data => {
            // Render the fetched content to the app container
            document.getElementById('app').innerHTML = data;
        })
        .catch(error => {
            console.error('Error fetching content:', error);
        });
}

// Function to handle navigation events (e.g., when a link is clicked)
function handleNavigation(event) {
    // Prevent the default behavior of the link
    event.preventDefault();
    // Get the route from the href attribute of the clicked link
    var route = event.target.getAttribute('href');
    // Render content based on the route
    renderContent(route);
    // Update the browser history
    history.pushState(null, null, route);
}

// Add event listeners to all links with the class 'nav-link'
var navLinks = document.querySelectorAll('a[href]');
navLinks.forEach(function(link) {
    link.addEventListener('click', handleNavigation);
});

// Event listener for popstate event (triggered when the user navigates using the browser back/forward buttons)
window.addEventListener('popstate', function(event) {
    // Render content based on the current URL
    renderContent(window.location.pathname);
});

// Initial rendering when the page loads
renderContent(window.location.pathname); // Render the content based on the current URL when the page loads
