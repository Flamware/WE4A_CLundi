function toggleVisibility(targetId) {
    const target = document.getElementById(targetId);

    if (target) {
        console.log("Attempting to toggle visibility for ID:", targetId);
        // Toggle visibility
        if (target.style.display === 'hidden') {
            target.style.display = 'visible';
        } else {
            target.style.display = 'hidden';
        }
    }
}