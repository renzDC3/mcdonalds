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
$latest_data = [];
foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $table) {
    $result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table GROUP BY product_name, code");

    while ($row = $result->fetch_assoc()) {
        $latest_data[$table][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <script src="loadLayout.js" defer></script>
    <title>Ordered Details</title>
</head>
<body>

   

    <div class="orderD">
        
        <table class="table-bordered" class="orderT">
        <caption style="text-align:center;">Order List</caption>
       
            <tr>
                <th class="td1">Category</th>
                <th class="td1">Product Name</th>
                <th class="td1">Code</th>
                <th class="td1">Available Boxes</th>
                
            </tr>
        
        <tbody>
            <?php 
            // Iterate through each category and check for low quantity
            foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $category): ?>
                <?php if (isset($latest_data[$category])): ?>
                    <?php foreach ($latest_data[$category] as $data): ?>
                        <?php if ($data['total_quantity'] <= 2): // Check if total_quantity is less than or equal to 5 ?>
                            <tr>
                            <td><?php echo htmlspecialchars($category); ?>
                                <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($data['code']); ?></td>
                                <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                              
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>


    <div class="messageExpired">
        <?php if (!empty($expiredProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are expired: <?php echo implode(', ', $expiredProducts); ?>. Please reorder.
            </div>
        <?php else: ?>
            <div>
           
            </div>
        <?php endif; ?>
    </div>



</body>
</html>

<?php
$conn->close();
?>
