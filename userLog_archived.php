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

$sql = "SELECT * FROM userlog_archived ORDER BY date_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="archived.css">
    
    <title>Archived User Log</title>
</head>
    <table>
        
        <thead>
            <tr>
                <th>Name</th>
                <th>Crew Number</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['crew_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_time']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">No records found</td></tr>';
            }
            ?>
        </tbody>
    </table>

   
</body>
</html>