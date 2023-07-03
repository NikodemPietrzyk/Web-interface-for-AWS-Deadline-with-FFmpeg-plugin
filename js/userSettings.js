
function redirect(location) {
    window.location.href = location;
}

function updateCheckboxStatus(checkbox, field) {
    // Get the updated status of the checkbox
    const isChecked = checkbox.checked ? 1 : 0;

    // Create an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'functions/userSettingsController.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Prepare the data to be sent
    const data = `field=${field}&isChecked=${isChecked}&userId=${userId}`;

    // Send the AJAX request
    xhr.send(data);
}