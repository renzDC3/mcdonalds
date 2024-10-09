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
foreach (['clamshell', 'can', 'powder', 'cups','sauces','paperbag','lids','utensil','boxes','granules','tissues','drinks'] as $table) {
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
    
    <title>Products</title>
</head>
<body>
<a class="expirelink" href="product_expiring.php">product expiring</a>
    <table class="table table-striped" id="productsLeft">
        <caption>Products</caption>
        <thead>
            <tr>
                <th class="td1">Category</th>
                <th class="td1">Product Name</th>
                <th class="td1">Available</th>
                <th class="td1">Code</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (['clamshell', 'can', 'powder', 'cups','sauces','paperbag','lids','utensil','boxes','granules','tissues','drinks'] as $category): ?>
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

    <table class="table table-striped" id="box-get">
        <caption>Products Get</caption>
        <thead>
            <tr>
                <td class="td1">Category</td>
                <td class="td1">Product Name</td>
                <td class="td1">Code</td>
                <td class="td1">Get</td>
                <td class="td1">Products Available</td>
            </tr>
        </thead>
        <tbody>
            <?php 
            $categories = ['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'];

            foreach ($categories as $category) {
                if (!empty($_SESSION['removed_quantities'][$category])) {
                    foreach ($_SESSION['removed_quantities'][$category] as $product_name => $removed) {
                        $total_quantity = 0;

                        if (isset($latest_data[$category])) {
                            foreach ($latest_data[$category] as $data) {
                                if ($data['product_name'] === $product_name) {
                                    $total_quantity = $data['total_quantity'];
                                    break; 
                                }
                            }
                        }

                        echo '<tr>';
                        echo '<td>' . ucfirst($category) . '</td>';
                        echo '<td>' . htmlspecialchars($product_name) . '</td>';
                        echo '<td>' . (isset($data['code']) ? htmlspecialchars($data['code']) : 'N/A') . '</td>';
                        echo '<td>' . htmlspecialchars($removed) . '</td>';
                        echo '<td>' . htmlspecialchars($total_quantity) . '</td>'; 
                        echo '</tr>';
                    }
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>
