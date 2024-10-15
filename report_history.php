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

// Query to fetch data from report_daily table, ordered by report_date
$query = "
    SELECT 
        product_name, 
        code, 
        in_quantity, 
        out_quantity, 
        available_quantity, 
        report_date 
    FROM 
        report_daily
    ORDER BY 
        report_date DESC
";
$result = $conn->query($query);

// Store reports by date in an associative array
$reportsByDate = [];
while ($row = $result->fetch_assoc()) {
    $reportsByDate[$row['report_date']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <style>
        body{
           background-color:black;
           
        }
        table {
            background-color:white;
            width: 100%;
            border: solid 1px rgb(73, 73, 73);
            
        }
        th, td {
            border: rgb(207, 217, 226) solid 1px;
            text-align: center;
        }
        h5{
            color:rgb(207, 217, 226);
            text-align:center;
        }
    </style>
</head>
<body>
    <div>
       
        <?php if (empty($reportsByDate)): ?>
            <p>No reports found.</p>
        <?php else: ?>
            <?php foreach ($reportsByDate as $reportDate => $reports): ?>
                <h5><?php echo htmlspecialchars($reportDate); ?></h5> <!-- Display the report date -->
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>In Quantity</th>
                            <th>Out Quantity</th>
                            <th>Available Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['code']); ?></td>
                                <td><?php echo htmlspecialchars($row['in_quantity']); ?></td>
                                <td><?php echo htmlspecialchars($row['out_quantity']); ?></td>
                                <td><?php echo htmlspecialchars($row['available_quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
        
    </div>
</body>
</html>

<?php
$conn->close();
?>
