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

$allProducts = [
    ['1PC', 2967, 'Clamshell'], ['2PC', 2987, 'Clamshell'], ['Fillet', 2957, 'Clamshell'],
    ['Spaghetti', 2968, 'Clamshell'], ['Cola', 5482, 'Can'], ['Fizz', 5924, 'Can'],
    ['Soda', 5479, 'Can'], ['Sprite can', 5447, 'Can'], ['BBQ powder', 7890, 'Powder'],
    ['Breader', 7554, 'Powder'], ['Cheese', 7653, 'Powder'], ['coating', 7542, 'Powder'],
    ['12oz cups', 5684, 'Cups'], ['16oz cups', 5788, 'Cups'], ['Mcflurry', 5799, 'Cups'],
    ['Sundae', 5435, 'Cups'], ['BBQ sauce', 8092, 'Sauces'], ['maple', 8463, 'Sauces'],
    ['sweet', 8322, 'Sauces'], ['syrups', 8657, 'Sauces'], ['A', 8092, 'Paperbag'],
    ['B', 8322, 'Paperbag'], ['C', 8463, 'Paperbag'], ['D', 8657, 'Paperbag'],
    ['12oz lids', 9542, 'Lids'], ['16oz lids', 9422, 'Lids'], ['coffee', 9267, 'Lids'],
    ['dome', 9533, 'Lids'], ['fork', 3532, 'Utensil'], ['knife', 3998, 'Utensil'],
    ['spoon', 3992, 'Utensil'], ['teaspoon', 2712, 'Utensil'], ['Happy meal Box', 6434, 'Boxes'],
    ['Mcshare box', 6534, 'Boxes'], ['brewed coffee granules', 3999, 'Granules'],
    ['coffee granules', 3993, 'Granules'], ['kitchen tissue', 7321, 'Tissues'],
    ['restroom tissue', 7753, 'Tissues'], ['serving tissue', 7231, 'Tissues'],
    ['CocaCola', 6257, 'Drinks'], ['ice coffee', 6260, 'Drinks'], ['Sprite', 6259, 'Drinks']
];

$latest_data = [];
$foundProducts = [];

foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $table) {
    $result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table GROUP BY product_name, code");
    while ($row = $result->fetch_assoc()) {
        $latest_data[$table][] = $row;
        $foundProducts[$row['product_name']] = $row['total_quantity'];
    }
}

$outOfStockProducts = [];
$lowQuantityProducts = [];

foreach ($allProducts as $product) {
    $productName = $product[0];
    $productCode = $product[1];
    $productCategory = $product[2];

    if (!isset($foundProducts[$productName])) {
        $outOfStockProducts[] = ['category' => $productCategory, 'product_name' => $productName, 'code' => $productCode, 'total_quantity' => 0];
    } else if ($foundProducts[$productName] <= 1) {
        $lowQuantityProducts[] = ['category' => $productCategory, 'product_name' => $productName, 'code' => $productCode, 'total_quantity' => $foundProducts[$productName]];
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <script src="loadLayout3.js" defer></script>
    <title>Ordered Details</title>
</head>
<body>
    <div class="orderD">
        <table class="table-bordered" class="orderT">
            <caption style="text-align:center;">Critical Product</caption>
            <tr>
                <th class="td1">Category</th>
                <th class="td1">Product Name</th>
                <th class="td1">Code</th>
                <th class="td1">Available Boxes</th>
            </tr>
            <tbody>
                <?php foreach ($outOfStockProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['code']); ?></td>
                        <td>0</td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($lowQuantityProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['code']); ?></td>
                        <td><?php echo htmlspecialchars($product['total_quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

 
        
</body>
</html>
<?php   /* if (!empty($outOfStockProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are out of stock: <?php echo implode(', ', array_map(function($product) {
                    return htmlspecialchars($product['product_name']);
                }, $outOfStockProducts)); ?>. Please reorder.
            </div>
        <?php endif; ?>
    </div>*/
