<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layoutnav.css">
    <script src="loadLayout3.js" defer></script>
    <title>Inventory System</title>
</head>
<style> 
#account{
  
  cursor: pointer;
}
.framesize{
  margin-top:100px;
  position:absolute;
  border: 1px solid;
  border-radius: 10px;
  padding: 10px;
  box-shadow: 5px 10px;
  height: 300px;
  width: 200px;
 
  top: 10px; 
  right: 20px; 
  z-index: 1000; 
  
}

#iframeContainer{
  
  
  top: 0;
  justify-content: flex-end;
 
  display: none;}
 
 

#accounticon{
height:40px;
width: 50px;}
#pos{position: absolute;

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
    <li><a class="btn-warning" href="index2.php">Home</a></li>
    <li><a class="btn-warning" href="products-update2.php">Stocks</a></li>
    <li><a class="btn-warning" href="report.php">Report</a></li>
    <li><a class="btn-warning" href="history.php">History</a></li>
    <li class="log_out"><a id="account" onclick="toggleIframe()"> <img src="accountIcon.png" id="accounticon"></a></li>


    </ul>

    
</nav>
    

<div id="iframeContainer">
    
    <iframe class="framesize" id="embeddedPage" src="/mcdonalds/account1.php"></iframe></div>
    <script>
    function toggleIframe() {
        const iframeContainer = document.getElementById('iframeContainer');
        iframeContainer.style.display = iframeContainer.style.display === 'none' ? 'block' : 'none';
    }</script>
    
    <section></section>
     
  <!-- <footer class="border-top footer text-muted ">
       <div class="bottom fontc-black">
        

            <p>&copy; MCFOOD / Quezon City / 09912328099 /<br>
            &copy; MCDONALDS RIZAL/ MALABON CITY /6065-84664</p>
-->
            
       
    </footer>
     </div>
</body>
</html>