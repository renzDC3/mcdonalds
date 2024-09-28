<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="login-register.css">
    <script src="loadLayout.js" defer></script>
</head>
<body>
<br><br><br><br>
<div class="container">
    <form action="login.php" method="post">
        <div class="form-group">
            <h1 style="color: white;">Login</h1><br>
            <input type="text" placeholder="Enter Crew Number:" name="crew_number" class="form-control" required>
        </div>
        <div class="form-btn"><br>
            <input type="submit" value="Login" name="login" class="btn btn-primary">
        </div><br>
    </form></div>
    <div class="message">
        <?php
        if (isset($_POST["login"])) {
            $crew_number = $_POST["crew_number"];
            
            if (empty($crew_number)) {
                echo "<div class='alert alert-danger'>Please enter your crew number</div>";
            } else {
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE crew_number = '$crew_number'";
                $result = mysqli_query($conn, $sql);

                // Debugging
                if (!$result) {
                    echo "<div class='alert alert-danger'>Query failed: " . mysqli_error($conn) . "</div>";
                } else {
                    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    if ($user) {
                        $_SESSION["user"] = $user["crew_number"];
                        $_SESSION["crew_name"] = $user["crew_name"];
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "<div class='alert alert-danger' id='alert'>Crew number does not match</div>";
                    }
                }
            }
        }
        ?>
    
</div>
<br><br>
</body>
</html>
