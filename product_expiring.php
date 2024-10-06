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

// Query to retrieve all received products and their details including batch number
$query = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time AS received_date, 
        DATE_ADD(rh.date_time, INTERVAL 3 MONTH) AS expiration_date,
        @batch_number := IF(@current_product = rh.product_name, @batch_number + 1, 1) AS batch_number,
        @current_product := rh.product_name
    FROM 
        received_history rh 
    JOIN 
        (SELECT product_name, code, MAX(date_time) as max_date 
        FROM received_history 
        WHERE action = 'added' 
        GROUP BY product_name, code) grouped_history 
    ON 
        rh.product_name = grouped_history.product_name 
        AND rh.code = grouped_history.code 
        AND rh.date_time = grouped_history.max_date,
        (SELECT @batch_number := 0, @current_product := '') AS vars
    WHERE 
        rh.product_name IN ('BBQ', 'Cheese', 'Breader', 'coating', 'BBQs', 'sweet', 'maple', 'syrups', 'coffee granules', 'brewed coffee granules', 'ice coffee', 'Sprite', 'CocaCola')
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
    <script src="loadLayout.js" defer></script>
    <title>Ordered Details</title>
</head>
<body>
<a href="ordered-details.php">Back</a>


    <div class="orderD">
       
        <table class="table-bordered" class="orderT">
            <thead><caption>Expiring Products</caption>

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
                                <td>{$row['expiration_date']}</td>
                                <td>Batch {$row['batch_number']}</td> <!-- Displaying the batch number -->
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>"; // Updated colspan to 6
                }
                ?>
            </tbody>
        </table>