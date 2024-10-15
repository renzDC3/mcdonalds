<?php
session_start();
if (!isset($_SESSION['manager_loggedin'])) {
    header('Location: login_manager.php');
    exit;
}

$full_name = $_SESSION["full_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="account.css">
    <title>Account</title>
</head>
<body>
    <div id="infoAccount">
        

        
        <li><a class="btn-warning" onclick="parent.location.href='feedback.php'">Give feedback</a></li>

        <div class="logoutbox">
            <a href="login_manager.php" class="logoutButton btn btn-warning" onclick="parent.location.href='logout2.php'; return false;">Logout</a>

        </div>
    </div>
</body>
</html>
