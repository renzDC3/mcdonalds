<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}


if (isset($_SESSION['manager_loggedin']) && $_SESSION['manager_loggedin'] === true) {
    header('Location: index2.php');
    exit();
}

if (isset($_POST["login"])) {
    $crew_number = $_POST["crew_number"];
    $password = $_POST["password"];

    if (!preg_match('/^\d{6,}$/', $crew_number)) {
        $error = "Crew number must be at least 6 digits.";
    } elseif (empty($password)) {
        $error = "Please enter your password.";
    } else {
        require_once "database.php";
        $sql = "SELECT * FROM users WHERE crew_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $crew_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["crew_number"];
                $_SESSION["full_name"] = $user["full_name"];

                // Log the login time
                date_default_timezone_set('Asia/Manila');
                $date_time = date('Y-m-d H:i:s');
                $full_name = $user["full_name"];

                $stmt_log = $conn->prepare("INSERT INTO mcdonalds_inventory.user_log (full_name, crew_number, date_time) VALUES (?, ?, ?)");
                $stmt_log->bind_param("sss", $full_name, $crew_number, $date_time);
                $stmt_log->execute();
                $stmt_log->close();

                // Redirect with JavaScript to set the active link
                echo "<script>
                        localStorage.setItem('activeLink', 'index.php');
                        window.location.href = 'index.php';
                      </script>";
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "Incorrect password and crew number. Please try again.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="login-register.css">
    <script src="loadLayout2.js" defer></script>
</head>

<body>
<br><br>

<a href="login_manager.php" style="
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
    margin-left:70px;
    margin-bottom:5px;
">
    Log in Manager
</a>

<div class="container">
    <form action="login.php" method="post">
        <div class="form-group">
            <h1 style="color: rgb(65, 65, 65) !important; text-decoration-line: underline;text-align:center;font-size:25px;font-weight: bold;">Crew Login</h1><br>
     </div><input placeholder="Enter Crew Number" name="crew_number" class="form-control" required><br>
            <input type="password" placeholder="Enter Password" name="password" class="form-control" required><br>
            <?php if (isset($error)): ?>
                <div style="font-size:13px; color:rgb(201, 58, 58);text-align:center;"><?= $error ?></div>
            <?php endif; ?>
           
            
        
        <input type="submit" value="Login" name="login" class="btn">
    </form>
</div>

<br><br>
</body>
</html>
