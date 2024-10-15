document.addEventListener('DOMContentLoaded', () => {
    fetch('layout3.php')
        .then(response => response.text())
        .then(data => {
            document.body.innerHTML = data + document.body.innerHTML;

            // Attach event listeners for navigation buttons
            const buttons = document.querySelectorAll('.btn-warning');
            buttons.forEach(button => {
                button.addEventListener('click', toggleActive);
            });

            // Set the active link based on local storage
            const activeLink = localStorage.getItem('activeLink');
            if (activeLink) {
                const activeElement = document.querySelector(`a[href="${activeLink}"]`);
                if (activeElement) {
                    activeElement.classList.add('active');
                }
            } else {
                // If no link is active, set the Home link as active by default
                document.getElementById('home-link').classList.add('active');
            }
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

function toggleActive(event) {
    const buttons = document.querySelectorAll('.btn-warning');
    buttons.forEach(button => {
        button.classList.remove('active');
    });

    // If the clicked element is an <a> tag, add 'active' class to it
    if (event.target.tagName === 'A') {
        event.target.classList.add('active');
        localStorage.setItem('activeLink', event.target.getAttribute('href'));
    }
    // If the clicked element is inside an <a> tag, add 'active' class to its parent <a> tag
    else {
        const parentAnchor = event.target.closest('a');
        parentAnchor.classList.add('active');
        localStorage.setItem('activeLink', parentAnchor.getAttribute('href'));
    }
}

