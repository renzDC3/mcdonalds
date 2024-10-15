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

    if (!preg_match('/^044\d{3}$/', $crew_number)) {
        $error = "Crew number must be exactly 6 digits and start with '044'.";
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
                $error = "Password does not match.";
            }
        } else {
            $error = "No account found with this crew number.";
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
<br><br><br><br>

<a href="login_manager.php">Log in as Manager</a>

<div class="container">
    <form action="login.php" method="post">
        <div class="form-group">
            <h1 style="color: white; text-align:center;">Crew Login</h1><br>
            <input placeholder="Enter Your Crew Number" name="crew_number" class="form-control" required><br>
            <input type="password" placeholder="Enter Your Password" name="password" class="form-control" required><br>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <div class="form-btn"><br>
                <input type="submit" value="Login" name="login" class="btn btn-primary" id="loginbtn">
            </div>
            <br>
        </div>
    </form>
</div>

<br><br>
</body>
</html>
