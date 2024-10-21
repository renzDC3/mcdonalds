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

// Query to retrieve all received products and their details including batch number
$query = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time AS received_date, 
        rh.expiration_date,  -- Fetch expiration_date directly from the database
        COUNT(*) OVER (PARTITION BY rh.product_name, rh.code ORDER BY rh.date_time) AS batch_number
    FROM 
        received_history rh
    WHERE 
        rh.product_name IN ('BBQ powder', 'Cheese', 'Breader', 'coating', 'BBQs', 'sweet', 'maple', 'syrups', 'coffee granules', 'brewed coffee granules', 'ice coffee', 'Sprite', 'CocaCola')
    ORDER BY 
        rh.product_name, rh.date_time";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="inventory.css">
    <script src="loadLayout3.js" defer></script>
    <title>Ordered Details</title>
</head>
<body>
<a class="expirelink" href="products-update2.php">Back</a>

<div class="orderD">
    <table class="table-bordered" class="orderT">
        <thead>
            <caption>Expiring Products</caption>
            <tr>
                <th>Product Name</th>
                <th>Code</th>
                <th>Boxes</th>
                <th>Received Date</th>
                <th>Expiration Date</th>
                <th>Batch</th>
           
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['product_name']}</td>
                            <td>{$row['code']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['received_date']}</td>
                            <td>
                                <form method='POST' action='update_expiration.php'>
                                    <input type='date' name='new_expiration_date' value='" . date('Y-m-d', strtotime($row['expiration_date'])) . "' required>
                                    <input type='hidden' name='product_code' value='{$row['code']}'>
                                    <input type='hidden' name='product_name' value='{$row['product_name']}'>
                                    <input type='submit' value='Update' class='btn btn-primary btn-sm'>
                                </form>
                            </td>
                            <td>Batch {$row['batch_number']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>"; // Updated colspan to 7
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
