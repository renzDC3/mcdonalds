<?php
session_start();
if (!isset($_SESSION['manager_loggedin'])) {
    header('Location: login_manager.php');
    exit;
}

require_once "database.php"; // Establish the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $crew_number = $_POST["crew_number"];
    $password = $_POST["password"];

    // Validate crew number format
    if (!preg_match('/^\d{6,}$/', $crew_number)) {
        $error = "Crew number must be exactly 6 digits";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if the crew number already exists
        $sql = "SELECT * FROM users WHERE crew_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $crew_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Crew number already exists. Please use a different crew number.";
        } else {
            // Proceed with inserting new user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (full_name, crew_number, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $full_name, $crew_number, $password_hash);

            if ($stmt->execute()) {
                header("Location: sign_up.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}

// Fetch users from the database
$sql = "SELECT id, full_name, crew_number, password FROM users";
$result = $conn->query($sql);

?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add crew</title>
    <script src="loadLayout3.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="login-register.css">
</head>
<style>
    button {
        
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
        }
        a {
            color: black;
            text-decoration: none;
        }

</style>
<body>

    <button><a href="index2.php">Back</a></button>
<br><br><br><br>
<div class="container2">
    <form action="sign_up.php" method="post">
        <div class="form-group2">
            <h1 style="color: black; margin-left:30px;font-size:25px;">Add Account</h1><br>
            <input type="text" placeholder="Input Full Name" name="full_name" class="input1" required><br>
            <input type="text" placeholder="Input Crew Number" name="crew_number" class="input1" required><br>
            <input type="password" placeholder="Input Password" name="password" class="input1" required><br>
            <?php if (isset($error)): ?>
                <div style="font-size:13px; color:rgb(201, 58, 58); text-align:center;"><?= $error ?></div>
            <?php endif; ?>
            <div class="form-btn"><br>
                <input type="submit" value="Add account" name="sign_up" class="btn btn-primary" id="signupbtn" style="margin-left:20px">
            </div>
            <br>
        </div>
    </form>
</div>

<div class="usertable">
    <h6 style="color:wheat">Crew account</h6>
    <table class="user">
        <thead>
            <tr style="text-align:center;">
                <th>Full Name</th>
                <th>Crew Number</th>
                <th>remove</th>
      
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["full_name"]) ?></td>
                        <td><?= htmlspecialchars($row["crew_number"]) ?></td>
                    
                        <td id="remove_"><button style="background-color: rgb(217, 56, 56); width:80px;height:35px;" onclick="removeUser(<?= $row['id'] ?>)">Remove</button></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function removeUser(userId) {
        if (confirm("Are you sure you want to remove this user?")) {
            window.location.href = "remove_user.php?id=" + userId;
        }
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
