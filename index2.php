<?php
session_start();
if (!isset($_SESSION['manager_loggedin'])) {
    header('Location: login_manager.php');
    exit;
}


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
$query = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time AS received_date, 
        DATE_ADD(rh.date_time, INTERVAL 1 second) AS expiration_date, 
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
$currentDate = new DateTime();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expirationDate = new DateTime($row['expiration_date']);
        // Check if the product has expired
        if ($expirationDate < $currentDate) {
            $expiredProducts[] = [
                'product_name' => $row['product_name'],
                'batch_number' => $row['batch_number'],
                'code' => $row['code'],
                'quantity' => $row['quantity']  // Include quantity in the expired products array
            ];
        }

    }}
// List of all product names
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

$foundProducts = [];

// Check each table for product quantities
foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $table) {
    $result = $conn->query("SELECT product_name FROM $table");
    while ($row = $result->fetch_assoc()) {
        $foundProducts[] = $row['product_name'];
    }
}

$outOfStockProducts = [];
foreach ($allProducts as $product) {
    if (!in_array($product[0], $foundProducts)) {
        $outOfStockProducts[] = $product[0] . " (Code: " . $product[1] . ")";
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
    <title>Home</title>
</head>
<style>
    button{
        width: 100px;
    }
    a{
        text-decoration:none
    }

</style>
<body>
    <button><a href="sign_up.php">Add Crew Account</a></button>
    <div class="messageExpired">
    <?php if (!empty($expiredProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are expired:
                <ul>
                <?php foreach ($expiredProducts as $product): ?>
                    <li>
                        <?php echo htmlspecialchars($product['product_name']) . " (Batch: " . $product['batch_number'] . ",Received: " . $product['quantity'] . ")"; ?>
                        <form action="remove_expired.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>">
                            <input type="hidden" name="code" value="<?php echo htmlspecialchars($product['code']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                        
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>



        <?php if (!empty($outOfStockProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are out of stock:
                <ul>
                <?php foreach ($outOfStockProducts as $product): ?>
                    <li><?php echo htmlspecialchars($product); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <h1>Inventory System</h1>
</body>
</html>
