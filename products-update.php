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

// Fetch the latest data for all product categories
$latest_data = [];
foreach (["clamshell", "can", "powder", "cups"] as $table) {
    $result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table GROUP BY product_name, code");

    while ($row = $result->fetch_assoc()) {
        $latest_data[$table][] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="inventory.css">
    <script src="loadLayout.js" defer></script>
    
    <title>Inventory</title>
</head>
<body>
    <h2>Today's Receive</h2>

    <table class="table table-striped" id="history">
        <thead>
            <tr>
                <th>Category</th>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (['clamshell', 'can', 'powder', 'cups','sauces','paper-bag','lids','utensil','boxes','granules','tissues','liquid-drinks'] as $category): ?>
                <?php if (isset($latest_data[$category])): ?>
                    <?php foreach ($latest_data[$category] as $data): ?>
                        <tr>
                            <td><?php echo ucfirst($category); ?></td>
                            <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($data['code']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
