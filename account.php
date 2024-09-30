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
    <link rel="stylesheet" href="account.css">

    
    <script src="loadLayout.js" defer></script>

    <title>Account</title>
    <style>
        .logout-box {
            display: flex;
            justify-content: center; /* Centers the content horizontally */
            align-items: center; /* Centers the content vertically */
            height: 100vh; /* Takes the full height of the viewport */
        }
        
        .logoutButton {
            color: aliceblue;
            background-color: black;
            border: 2px white;
            width: 220px;
            height: 80px;
            font-size: 45px;
            text-align: center;
            padding: auto;
            cursor: pointer;
            display: block;
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <!-- Display User Info-->
        <a href="logout.php" class="logoutButton btn btn-warning">Logout</a>
    </div>
</body>
</html>




