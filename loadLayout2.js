// loadLayout.js
document.addEventListener('DOMContentLoaded', () => {
    fetch('layout2.php')
        .then(response => response.text())
        .then(data => {
            document.body.innerHTML = data + document.body.innerHTML;
        })
        .catch(error => console.error('Error loading layout:', error));
});