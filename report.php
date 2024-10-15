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

// Handle the report generation and data transfer to report_daily table
if (isset($_POST['report'])) {
    $query = "
        INSERT INTO report_daily (product_name, code, in_quantity, out_quantity, available_quantity, report_date)
        SELECT product_name, code, 
               SUM(CASE WHEN action = 'added' THEN quantity ELSE 0 END) as in_quantity,
               SUM(CASE WHEN action = 'removed' THEN quantity ELSE 0 END) as out_quantity,
               (SELECT IFNULL(SUM(quantity), 0) FROM (
                    SELECT product_name, SUM(quantity) AS quantity FROM clamshell GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM can GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM powder GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM cups GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM sauces GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM paperbag GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM lids GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM utensil GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM boxes GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM granules GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM tissues GROUP BY product_name
                    UNION ALL
                    SELECT product_name, SUM(quantity) AS quantity FROM drinks GROUP BY product_name
                ) AS all_products WHERE all_products.product_name = received_history.product_name) AS available_quantity,
               CURDATE() as report_date
        FROM received_history
        WHERE DATE(date_time) = CURDATE()
        GROUP BY product_name, code
        ON DUPLICATE KEY UPDATE
            in_quantity = VALUES(in_quantity),
            out_quantity = VALUES(out_quantity),
            available_quantity = VALUES(available_quantity),
            report_date = VALUES(report_date)
    ";
    $conn->query($query);

    // Set a flag to indicate data has been reported
    $_SESSION['reported'] = true;
    // Set the alert message
    $alertMessage = "Report has been successfully submitted!";
}

// Query to get today's transactions if not reported yet
$query = "
    SELECT 
        product_name, 
        code, 
        SUM(CASE WHEN action = 'added' THEN quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN action = 'removed' THEN quantity ELSE 0 END) as out_quantity
    FROM received_history
    WHERE DATE(date_time) = CURDATE()
    GROUP BY product_name, code
";

$result = $conn->query($query);

// Get available products from all product tables
$productTables = ['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'];
$availableProducts = [];

// Initialize available products
foreach ($productTables as $table) {
    $availableQuery = "SELECT product_name, SUM(quantity) AS total_quantity FROM $table GROUP BY product_name";
    $availableResult = $conn->query($availableQuery);
    while ($row = $availableResult->fetch_assoc()) {
        $availableProducts[$row['product_name']] = $row['total_quantity'];
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
    <link rel="stylesheet" href="style.css">
    <script src="loadLayout3.js" defer></script>
    <style>
        
        table {
            box-shadow: 8px 8px; 
            margin-left: auto;
            margin-right: auto;
            border-radius:10px;
            width: 700px;
           
            background-color:white;
        }
        th, td {
            border: rgb(207, 217, 226) solid 1px;
        }
        th{
            color:white;
            background-color:rgb(73, 73, 73);
        }
        button {
            text-align:center;
            margin-top: 30px;
            margin-left: 870px;
            padding:8px;
            width: 70px;
            font-weight:bold;
            color:rgb(99, 132, 161);
            
        }
        iframe{
            margin-top:80px;
            margin-left: auto;
            margin-right: auto;
        }
        h4{ text-align:center;
           
            font-size:30px;
            letter-spacing: -.15ch;
            line-height: .75;
        }
        .submmited{
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            text-align:center;
            padding:10px;
            border-radius:50px;
            background-color:white;
            

        }
    </style>
</head>
<body>
    <br>
    <h4>Daily Report In and Out Product <?php/* echo date('Y-m-d'); */?></h4>
    <br>
    
    <div>
    <!-- this table displays current data of today -->
    <table>
    
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Code</th>
                <th>In</th>
                <th>Out</th>
                <th>Products Available</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                        <td><?php echo htmlspecialchars($row['in_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['out_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($availableProducts[$row['product_name']] ?? 0); ?></td> <!-- Display available products -->
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No transactions today or data has been reported.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table></div>
    <form method="post">
        <button type="submit" name="report" id="reportButton">Report</button>
    </form>
    <!-- Alert message -->
    <?php if (isset($alertMessage)): ?>
        <div role="alert">
           <div class="submmited"> <?php echo $alertMessage; ?></div>
        </div>
    <?php endif; ?>
    <iframe src="report_history.php" height="200" width="700" title="Iframe Example"></iframe>


    <br><br>
</body>
</html>

<?php
$conn->close();
?>
