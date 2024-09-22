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
    <link rel="stylesheet" href="inventory1.css">
    
    <script src="loadLayout.js" defer></script>
    
    <title>Inventory</title>

</head>
<body>
        

        
            <div class ="inventory-list">
            <ul class="tertiary">
            
            <li><a onclick="toggleIframe('#frame1')">CLAMSHELL</a></li>
            <div class="iframeContainer" id='frame1'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/clamshell.php"> </iframe>
            </div>
            
            <li><a onclick="toggleIframe('#frame2')">POWDER</a></li>
            <div class="iframeContainer" id ='frame2'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/powder.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame3')">SAUCES</a></li>
            <div class="iframeContainer" id ='frame3'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/sauces.php"> </iframe>
            </div>
            
            <li><a onclick="toggleIframe('#frame4')">LIDS</a></li>
            <div class="iframeContainer" id ='frame4'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/LIDS.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame5')">BOXES</a></li>
            <div class="iframeContainer" id ='frame5'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/boxes.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame6')">TISSUES</a></li>
            <div class="iframeContainer" id ='frame6'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/tissues.php"> </iframe>
            </div>
            
        <!--Second row-->
            </ul>
            <ul class="tertiary">
            
            <li><a onclick="toggleIframe('#frame7')">CAN</a></li>
            <div class="iframeContainer" id ='frame7'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/can.php"> </iframe>

            </div>
            
            <li><a onclick="toggleIframe('#frame8')">CUPS</a></li>
            <div class="iframeContainer" id ='frame8'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/powder.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame9')">PAPER BAG</a></li>
            <div class="iframeContainer" id ='frame9'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/paper-bag.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame10')">UTENSIL</a></li>
            <div class="iframeContainer" id ='frame10'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/utensil.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame11')">GRANULES</a></li>
            <div class="iframeContainer" id ='frame11'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/granules.php"> </iframe>
            </div>

            <li><a onclick="toggleIframe('#frame12')">DRINKS</a></li>
            <div class="iframeContainer" id ='frame12'>
            <iframe id="embeddedPage" src="/mcdonalds/inventory/drinks.php"> </iframe>
            </div>            </ul>


            </div>
            <div class="bottom-buttons">
    

</div>



</body>
</html>

</body>
</html>