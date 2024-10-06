<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layout1.css">
    <script src="loadLayout.js" defer></script>
    <link rel="stylesheet" href="account1.css">
    <title>Inventory System</title>
</head>
<style>

#account{
  
    cursor: pointer;
}
.framesize{
    position:absolute;
    border: 1px solid;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 5px 10px;
    height: 300px;
    width: 200px;
    margin-top:80px;
    top: 10px; 
    right: 20px; 
    z-index: 1000; 
    
}
#iframeContainer{
    
    position:absolute;
    margin-left:1340px;
    justify-content: flex-end;
    display: none;}
   

 #accounticon{
height:40px;
width: 50px;

  
}
   
   


</style>
<body>
    
    <nav>
    <input type="checkbox" id="check">
    <label for="check" class="check-btn">
        <i class="fas fa-bars"></i>
    </label>
    <label class="logo">Inventory System</label>
    <img src="topicon.png" alt="McDo Icon" class="top-icon">
    <ul>
    <li><a class="btn-warning" href="index.php">Home</a></li>
    <li><a class="btn-warning" href="inventory.php">Inventory</a></li>
    <li><a class="btn-warning" href="products-update.php">Products</a></li>
    <li><a class="btn-warning" href="ordered-details.php">Reorder</a></li>
    <li><a class="btn-warning" href="history.php">History</a></li>
    <li><a class="btn-warning" href="feedback.php">Feedback</a></li>
    <li class="log_out"><a id="account" onclick="toggleIframe()"> <img src="accountIcon.png" id="accounticon"></a></li>
    </ul>

    
</nav>
<div id="iframeContainer">
    <iframe class="framesize" id="embeddedPage" src="/mcdonalds/account.php"></iframe>
</div>

<script>
    function toggleIframe() {
        const iframeContainer = document.getElementById('iframeContainer');
        iframeContainer.style.display = iframeContainer.style.display === 'none' ? 'block' : 'none';
    }

    function toggleActive(event) {
        const buttons = document.querySelectorAll('.btn-warning');
        buttons.forEach(button => {
            button.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    document.querySelectorAll('.btn-warning').forEach(button => {
        button.addEventListener('click', toggleActive);
    });
</script>

    
    
   
     
  <!-- <footer class="border-top footer text-muted ">
       <div class="bottom fontc-black">
        

            <p>&copy; MCFOOD / Quezon City / 09912328099 /<br>
            &copy; MCDONALDS RIZAL/ MALABON CITY /6065-84664</p>

            
       
    </footer>
     </div>-->
</body>
</html>
