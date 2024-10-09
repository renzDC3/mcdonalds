<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layout1.css">
    <script src="loadLayout.js" defer></script>
    <title>Inventory System</title>
</head>
<style> #accounticon{
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