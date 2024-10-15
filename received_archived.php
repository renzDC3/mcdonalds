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

// Modify the SQL query to include the batch column
$sql = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time, 
        rh.batch  -- Include batch column
    FROM 
        received_archived rh 
    WHERE 
        rh.action = 'added' 
    ORDER BY 
        rh.batch DESC,  -- You can also order by batch here
        rh.date_time DESC"; 

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="archived.css">
  
    <title>Received History</title>
</head>
<body>


<div>
        
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Code</th>
                    <th>Boxes Received</th>
                    <th>Date & Time</th>
                    <th>Batch</th>  <!-- Add Batch Header -->
                </tr>
            </thead>
            <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['code']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_time']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['batch']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No records found</td></tr>';  
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
