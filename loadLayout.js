
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

 // Add active class to the current button (highlight it)
 var navList = document.getElementById("nav-list");
 var btns = navList.getElementsByClassName("btn");

 for (var i = 0; i < btns.length; i++) {
     btns[i].addEventListener("click", function() {
         var current = navList.getElementsByClassName("active");
         if (current.length > 0) {
             current[0].classList.remove("active");
         }
         this.classList.add("active");
     });
 }




