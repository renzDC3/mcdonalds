// loadLayout.js
document.addEventListener('DOMContentLoaded', () => {
    fetch('layout.php')
        .then(response => response.text())
        .then(data => {
            document.body.innerHTML = data + document.body.innerHTML;
        })
        .catch(error => console.error('Error loading layout:', error));
});

function toggleIframe() {
    const iframeContainer = document.querySelector('.iframeContainer');
    if (iframeContainer.style.display === 'none' || iframeContainer.style.display === '') {
        iframeContainer.style.display = 'block'; // Show the iframe
    } else {
        iframeContainer.style.display = 'none'; // Hide the iframe
    }
}
