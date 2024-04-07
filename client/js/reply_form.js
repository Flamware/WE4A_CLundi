function toggleTextArea() {
    var textAreaContainer = document.getElementById("textAreaContainer");
    if (textAreaContainer.style.display === "none") {
        textAreaContainer.style.display = "block";
    } else {
        textAreaContainer.style.display = "none";
    }
}

function hideTextArea() {
    var textAreaContainer = document.getElementById("textAreaContainer");
    textAreaContainer.style.display = "none";
}
