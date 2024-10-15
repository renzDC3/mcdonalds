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
<br><br><br><br>

<a href="login.php">Log in as Crew</a>

<div class="container1">
    <form action="login_manager.php" method="post">
        <div class="form-group">
            <h1 style="color: white; text-align:center;">Manager Login</h1><br>
            <input type="password" placeholder="Enter Password" name="password" class="form-control" required><br>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <div class="form-btn"><br>
                <input type="submit" value="Login" name="login_manager" class="btn btn-primary" id="loginbtn">
            </div>
            <br>
        </div>
    </form>
</div>

<br><br>
</body>
</html>
