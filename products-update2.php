<?php
session_start();
if (!isset($_SESSION['manager_loggedin'])) {
    header('Location: login_manager.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all possible products from a master products table
$master_products_query = "SELECT product_name, code, category FROM master_products"; 
$master_products_result = $conn->query($master_products_query);

$master_products = [];
if ($master_products_result->num_rows > 0) {
    while ($row = $master_products_result->fetch_assoc()) {
        $master_products[$row['category']][] = $row; // Group by category
    }
}

// Fetch the latest data for all product categories
$latest_data = [];
foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $table) {
    $result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table GROUP BY product_name, code");

    while ($row = $result->fetch_assoc()) {
        $latest_data[$table][$row['product_name']] = $row; // Use product_name as key for easy lookup
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
    <script src="loadLayout3.js" defer></script>
    
    <title>Products</title>
</head>
<body>
    
<a class="expirelink" href="product_expiring.php">Product Expiring</a>
<table class="table table-striped" id="productsLeft">
    <caption style="color:white;">Products</caption>
    <thead>
        <tr>
            <th class="td1">Category</th> 
            <th class="td1">Product Name</th>
            <th class="td1">Available</th>
            <th class="td1">Code</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($master_products as $category => $products): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo ucfirst($category); ?></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td>
                        <?php
                        // Check if the product has been recorded in the latest data
                        $total_quantity = 0; // Initialize total quantity
                        foreach ($latest_data as $table => $data) {
                            if (isset($data[$product['product_name']])) {
                                $total_quantity += $data[$product['product_name']]['total_quantity'];
                            }
                        }
                        echo htmlspecialchars($total_quantity > 0 ? $total_quantity : "0"); // Display total quantity
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($product['code']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

   
</body>
</html>
