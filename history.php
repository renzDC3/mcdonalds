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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="table-history.css">
    <script src="loadLayout.js" defer></script>
    <title></title>
</head>










<body>
<<<<<<< HEAD

    <div class ="history">
        <br>
    <li><a onclick="toggleIframe('#frame20')">Order Received</a></li>
            <div class="iframeContainer" id ='frame20'>
            <iframe class="framesize" id="embeddedPage" src="/mcdonalds/order-received.php"> </iframe>
            </div>

    
    
            <li><a onclick="toggleIframe('#frame21')">User Log</a></li>
            <div class="iframeContainer" id ='frame21'>
            <iframe class="framesize" id="embeddedPage" src="/mcdonalds/user-log.php"> </iframe>
            </div>   


            <li><a onclick="toggleIframe('#frame22')">Product History</a></li>
            <div class="iframeContainer" id ='frame22'>
            <iframe class="framesize" id="embeddedPage" src="/mcdonalds/Product-History.php"> </iframe>
            </div>
    </div>


=======
    <h1>History</h1>
    
>>>>>>> e0694a150b920f99c1be508f496973539fac58f4
</body>
</html>




