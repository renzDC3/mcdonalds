<?php
session_start();
if (isset($_SESSION["manager_loggedin"]) || isset($_SESSION["user"])) {
    header("Location: index2.php");
    exit();
}
// rest of your login_manager.php code...


if (isset($_POST['login_manager'])) {
    $password = $_POST['password'];
    $stored_password = '@mcdo2328099';

    if ($password === $stored_password) {
        $_SESSION['manager_loggedin'] = true;
        header('Location: index2.php');
        exit;
    } else {
        $error = "Incorrect password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="login-register.css">
    <script src="loadLayout2.js" defer></script>
</head>
<body>
<br><br>

<a href="login.php"style="
    color: black;
    font-size: 14px;
    font-weight: bold;
    font-size: 0.9rem;
    background: rgba(255, 255, 255, 0.283);
    width: 100%;
    box-sizing: border-box;
    padding-inline: 0.5em;
    padding-block: 0.7em;
    border: none;
    border-bottom: var(--border-height) solid var(--border-before-color);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.313);
    width:130px;
    border-radius:10px;
    text-align:center;
    margin-left:70px;
    margin-bottom:5px;
    
">Log in Crew</a>

<div class="container1">
    <form action="login_manager.php" method="post">
        <div class="form-group">
            <h1 style="color: rgb(65, 65, 65) !important; text-decoration-line: underline;text-align:center;font-size:25px;font-weight: bold;">Manager Login</h1><br>
            </div><input type="password" placeholder="Enter Password" name="password" class="form-control" required><br>
            <?php if (isset($error)): ?>
                <div style="font-size:13px; color:rgb(201, 58, 58);text-align:center;"><?= $error ?></div>
            <?php endif; ?>        
        <input type="submit" value="Login" name="login_manager" class="btn">
    </form>
    </div>

<br><br>
</body>
</html>
