<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit();
}
$crew_name = $_SESSION["crew_name"];

// Set the timezone
date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$latest_data = [];
foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $table) {
    $result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table GROUP BY product_name, code");

    while ($row = $result->fetch_assoc()) {
        $latest_data[$table][] = $row;
    }
}
$query = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time AS received_date, 
        DATE_ADD(rh.date_time, INTERVAL 3 MONTH) AS expiration_date, 
        ROW_NUMBER() OVER (PARTITION BY rh.product_name, rh.code ORDER BY rh.date_time) AS batch_number
    FROM 
        received_history rh 
    WHERE 
        rh.action = 'added' 
        AND rh.product_name IN ('BBQ', 'Cheese', 'Breader', 'coating', 'BBQs', 'sweet', 'maple', 'syrups', 'coffee granules', 'brewed coffee granules', 'ice coffee', 'Sprite', 'CocaCola')
    ORDER BY 
        rh.product_name, rh.code";

$result = $conn->query($query);

$expiredProducts = [];
$outOfStockProducts = []; // New array for out of stock products
$currentDate = new DateTime();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expirationDate = new DateTime($row['expiration_date']);
        // Check if the product has expired
        if ($expirationDate < $currentDate) {
            $expiredProducts[] = $row['product_name'] . " (Batch: " . $row['batch_number'] . ")";
        }
        
        // Check if the product is out of stock
        if ($row['quantity'] <= 0) {
            // Insert into out_of_stock table
            $stmt = $conn->prepare("INSERT INTO out_of_stock (product_name, code, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $row['product_name'], $row['code'], $row['quantity']);
            $stmt->execute();
            $stmt->close();
            
            $outOfStockProducts[] = $row['product_name'] . " (Code: " . $row['code'] . ")";
        }
    }
}

$conn->close();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <script src="loadLayout.js" defer></script>
    <title>Home</title>
</head>
<body>

    <div class="messageExpired">
        <?php if (!empty($expiredProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are expired: <?php echo implode(', ', $expiredProducts); ?>
            </div>
        <?php endif; ?>

        
        <?php   // Iterate through each category and check for low quantity
            foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $category): ?>
                <?php if (isset($latest_data[$category])): ?>
                    <?php foreach ($latest_data[$category] as $data): ?>
                        <?php if ($data['total_quantity'] <= 0):?>
            <div class="alert alert-danger" role="alert">
                The product: <td><?php echo htmlspecialchars($data['product_name']); ?></td> are out of stock Please restock.
            </div>
            <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
    </div>

    <h1>Inventory System</h1>
</body>
</html>
