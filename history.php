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

$sql = "SELECT * FROM received_history ORDER BY date_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="history_table.css">
    <script src="loadLayout.js" defer></script>
    <title>Received History</title>
</head>
<body>
    <table class="table-d">
        <tr>
            <td class="td1">Product Name</td>
            <td class="td1">Code</td>
            <td class="td1">Boxes</td>
            <td class="td1">Date&Time</td>
            <td class="td1">Category</td>
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['code']) . '</td>';
                echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                echo '<td>' . htmlspecialchars($row['date_time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">No records found</td></tr>';
        }
        ?>
    </table>


    <div class ="history">
            <li><a href="history.php"class="abox">Order Received</a></li>
    </div>
    <div class ="history">
            <li><a href="user_log.php"class="abox">User Log</a></li>
    </div>

    <div class ="history">
            <li><a href="crew_got.php"class="abox">Crew Got</a></li>
    </div>



</body>
</html>




