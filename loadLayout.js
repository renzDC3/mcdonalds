
document.addEventListener('DOMContentLoaded', () => {
    fetch('layout.php')
        .then(response => response.text())
        .then(data => {
            document.body.innerHTML = data + document.body.innerHTML;
        })
        .catch(error => console.error('Error loading layout:', error));
});




function toggleIframe() {
    const iframeContainer = document.getElementById('iframeContainer');
    if (iframeContainer.style.display === 'flex') {
        iframeContainer.style.display = 'none';
    } else {
        iframeContainer.style.display = 'flex';
    }
}




