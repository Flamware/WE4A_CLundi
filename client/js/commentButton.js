function toggleVisibility(targetId) {
    const target = document.getElementById(targetId);
    if (target) {
        console.log("Attempting to toggle visibility for ID:", targetId);
        // Toggle visibility
        if (target.style.display === 'none') {
            target.style.display = 'block';
        } else {
            target.style.display = 'none';
        }
    }
}