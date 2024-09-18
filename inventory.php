<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="inventory.css">
    
    <script src="loadLayout.js" defer></script>
    
    <title>Inventory</title>

</head>
<body>
        

        
            <div class ="inventory-list">
            <ul class="tertiary">
                <li><a href="/mcdonalds/inventory/clamshell.php">CLAMSHELL</a></li>
                <li><a href="/mcdonalds/inventory/powder.php">POWDER</a></li>
                <li><a href="/mcdonalds/inventory/sauces.php" >SAUCES</a></li>
                <li><a href="/mcdonalds/inventory/lids.php">LIDS</a></li>
                <li><a href="/mcdonalds/inventory/boxes.php">BOXES</a></li>
                <li><a href="/mcdonalds/inventory/tissues.php">TISSUES</a></li>
            </ul>
            <ul class="tertiary">
                <li><a href="/mcdonalds/inventory/can.php">CAN</a></li>
                <li><a href="/mcdonalds/inventory/cups.php" >CUPS</a></li>
                <li><a href="/mcdonalds/inventory/paper-bag.php" >PAPER BAG</a></li>
                <li><a href="/mcdonalds/inventory/utensil.php" >UTENSIL</a></li>
                <li><a href="/mcdonalds/inventory/granules.php">GRANULES</a></li>
                <li><a href="/mcdonalds/inventory/drinks.php" >DRINKS</a></li>
            </ul>


            </div>


        


        
    














        
    
</body>
</html>