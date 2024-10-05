<?php
session_start(); 

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("Location:index.php");
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

// Fetch the removal history
$product_history = [];
$stmt = $conn->prepare("SELECT product_name, quantity, date_time FROM received_history WHERE action = 'removed' ORDER BY date_time DESC");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $product_history[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <script src="loadLayout.js" defer></script>
    <link rel="stylesheet" href="history_table.css">

    <title>Crew Got</title>
</head>
<body>
<div class ="history">
    <li><a href="history.php" class="abox">Order Received</a></li>
</div>
<div class ="history">
    <li><a href="user_log.php" class="abox">User Log</a></li>
</div>
<div class ="history">
    <li><a href="crew_got.php" class="abox">Crew Got</a></li>
</div>

<table class="table-d">
    <caption>Crew Got History</caption>
    <thead>
        <tr>
            <td class="td1">Product Name</td>
            <td class="td1">Quantity</td>
            <td class="td1">Date & Time</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($product_history as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry['product_name']); ?></td>
                <td><?php echo htmlspecialchars($entry['quantity']); ?></td>
                <td><?php echo htmlspecialchars($entry['date_time']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
