<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";

    $full_name = $_POST["full_name"];
    $crew_number = $_POST["crew_number"];
    $password = $_POST["password"];

    // Validate crew number
    if (!preg_match('/^044\d{3}$/', $crew_number)) {
        $error = "Crew number must be exactly 6 digits and start with '044'.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, crew_number, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $full_name, $crew_number, $password_hash);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
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
    <title>Sign Up</title>
    <script src="loadLayout2.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="login-register.css">
</head>
<body>
<br><br><br><br>
<div class="container">
    <form action="sign_up.php" method="post">
        <div class="form-group">
            <h1 style="color: white; text-align:center;font-size:25px;">Creat Account</h1><br>
            <input type="text" placeholder="Enter Your Full Name" name="full_name" class="form-control" required><br>
            <input type="text" placeholder="Enter Your Crew Number" name="crew_number" class="form-control" required><br>
            <input type="password" placeholder="Enter Your Password" name="password" class="form-control" required><br>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <div class="form-btn"><br>
                <input type="submit" value="Sign Up" name="sign_up" class="btn btn-primary" id="signupbtn">
            </div>
            <a href="login.php" style=" text-align:center;color:white;">Back to Login</a>
            <br>
        </div>
    </form>
</div>
</body>
</html>
