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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="login-register.css">
    <script src="loadLayout2.js" defer></script>
</head>
<body>
<br><br><br><br>
<div class="container">
    <form action="login.php" method="post">
        <div class="form-group">
            <h1 style="color: white; text-align:center;">Login</h1><br>
            <input type="password" placeholder="Enter Your Password:" name="crew_number" class="form-control" required>
        
        <div class="form-btn"><br>
            <input type="submit" value="Login" name="login" class="btn btn-primary" id="loginbtn">
        </div><br>
    </form></div></div>
    <div class="message">
        <?php
        if (isset($_POST["login"])) {
            $crew_number = $_POST["crew_number"];
            
            if (empty($crew_number)) {
                echo "<div class='alert alert-danger'>Please enter your crew number</div>";
            } else {
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE crew_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $crew_number);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $_SESSION["user"] = $user["crew_number"];
                    $_SESSION["crew_name"] = $user["crew_name"];
                   
                    

                    // Log the login time
                    date_default_timezone_set('Asia/Manila');
                    $date_time = date('Y-m-d H:i:s');
                    $crew_name = $user["crew_name"];
                    $crew_number = $user["crew_number"];

                    $stmt_log = $conn->prepare("INSERT INTO mcdonalds_inventory.user_log (crew_name, crew_number, date_time) VALUES (?, ?, ?)");
                    $stmt_log->bind_param("sss", $crew_name, $crew_number, $date_time);
                    $stmt_log->execute();
                    $stmt_log->close();

                    header("Location: index.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger' id='alert'>Password does not match</div>";
                }
            }
        }
        ?>
    </div>

<br><br>
</body>
</html>
