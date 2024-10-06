<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user_log ORDER BY date_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="history_table.css">
    <script src="loadLayout.js" defer></script>
    <title>User Log</title>
</head>
<body><h1 class="my-4">User Log</h1>
    <table class="table-d" id="user_log">
        
        <thead>
            <tr>
                <td class="td1">Name</td>
                <td class="td1">Crew Number</td>
                <td class="td1">Date & Time</td>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['crew_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['crew_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_time']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">No records found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="history">
        <li><a href="history.php" class="abox">Order Received</a></li>
    </div>
    <div class="history">
        <li><a href="user_log.php" class="abox">User Log</a></li>
    </div>
    <div class="history">
        <li><a href="crew_got.php" class="abox">Crew Got</a></li>
    </div>
</body>
</html>
