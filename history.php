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

// Updated query to include batch numbers and sort by batch ascending
$sql = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time,
        COUNT(*) OVER (PARTITION BY rh.product_name, rh.code ORDER BY rh.date_time) AS batch
    FROM 
        received_history rh 
    WHERE 
        rh.action = 'added' 
    ORDER BY 
        batch DESC,  -- Sort by batch in descending order
        rh.date_time DESC"; 

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
    <h3 class="my-4">Received History</h3>
    <div>
        <table class="table-d">
            <thead>
                <tr>
                   
                    <th class="td1">Product Name</th>
                    <th class="td1">Code</th>
                    <th class="td1">Boxes Received</th>
                    <th class="td1">Date & Time</th>
                    <th class="td1">Batch</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                   
                    echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['code']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                  
                    echo '<td>' . htmlspecialchars($row['date_time']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['batch']) . '</td>'; // Displaying the batch number
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No records found</td></tr>'; // Updated colspan to 5
            }
            ?>
            </tbody>
        </table>
    </div>

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
