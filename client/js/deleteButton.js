function submitDeleteForm(button) {
    var form = button.closest('.deleteForm');
    if (!form) {
        console.error('Form not found');
        return;
    }

    var formData = new FormData(form);
    var url = form.getAttribute('action');
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            var response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {
                if (response.success) {
                    var itemToDelete = null;

                    if (button.closest('.comment')) {
                        itemToDelete = button.closest('.comment');
                    } else if (button.closest('.story')) {
                        itemToDelete = button.closest('.story');
                    }

                    if (itemToDelete) {
                        itemToDelete.remove();
                    } else {
                        console.error('Item to delete not found');
                    }
                } else {
                    console.error('Delete failed:', response.message);
                    showError(response.message);
                }
            } else {
                console.error('HTTP Error:', response.message);
                showError(response.message);
            }
        }
    };

    xhr.open('POST', url);
    xhr.send(formData);
}
