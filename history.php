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

// Function to get the next batch number
function getNextBatch($conn, $productName) {
    $sql = "SELECT COUNT(*) as count FROM received_history WHERE product_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] + 1; // Incrementing the count gives the next batch number
}

// Handling archive request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive'])) {
    $archive_sql = "
    INSERT INTO received_archived (product_name, code, quantity, date_time, action, batch)
    SELECT product_name, code, quantity, date_time, action, batch
    FROM received_history
    WHERE action = 'added'";


    if ($conn->query($archive_sql) === TRUE) {
        $delete_sql = "DELETE FROM received_history WHERE action = 'added'";
        $conn->query($delete_sql);
        $archive_message = "Records successfully archived.";
    } else {
        $archive_message = "Error archiving records: " . $conn->error;
    }
}

// Fetching received history
$sql = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time, 
        rh.batch
    FROM 
        received_history rh 
    WHERE 
        rh.action = 'added' 
    ORDER BY 
        rh.batch DESC,  -- Sort by batch in descending order
        rh.date_time DESC"; 

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="history_table.css">
    <script src="loadLayout.js" defer></script>
    
    <title>Received History</title>
</head>
<style>
    .buttonarchived{
        margin-top:175px;
        width: 100px;
        position: absolute;
        right: 138px;
        background-color:rgb(215, 66, 66);
        top: 5px;
        
    }

    .button{
        cursor:pointer;
        margin-top:120px;
        position: absolute;
        width: 132px;
        background-color:black;
        color:white;
        text-decoration:none;
    }
    #myframe{
        margin-top:147px;
        position:absolute;
        border-radius: 5px;
        overflow: scroll;
        width: 473px;
        height:200px;
        z-index:1;
        visibility: hidden; /* Start hidden but keep space */
        border: none; /* Optional: remove border */
        
       
    }
    
        
    
</style>
<body>

<h3 class="my-4">Restock History</h3>
  
<form method="post" action="history.php">
    <button class="buttonarchived" type="submit" name="archive" class="btn btn-warning">Archive</button>
</form>

<a class="button" onclick="toggleFrame()">Open Archived</a>

<iframe  id="myframe"></iframe>

<script>
function toggleFrame() {
    var frame = document.getElementById("myframe");
    if (frame.style.visibility === "hidden" || frame.style.visibility === "") {
        frame.src = "received_archived.php"; // Set the source only when opening
        frame.style.visibility = "visible"; // Show the iframe
    } else {
        frame.style.visibility = "hidden"; // Hide the iframe without removing space
        frame.src = ""; // Optionally clear the source when hiding
    }
}
</script>
<div>
    <table class="table-d">
        <thead>
            <tr>
                <th class="td1">Product Name</th>
                <th class="td1">Code</th>
                <th class="td1">Boxes Received</th>
                <th class="td1">Date & Time</th>
                <th class="td1">Batch</th> <!-- Add batch column -->
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
                echo '<td>' . htmlspecialchars($row['batch']) . '</td>'; // Display batch number
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">No records found</td></tr>'; // Updated colspan
        }
        ?>
        </tbody>
    </table>
</div>

<div class ="history1">
    <li><a href="history.php" class="abox">Restock product</a></li>
</div>
<div class ="history2">
    <li><a href="user_log.php" class="abox">User Log</a></li>
</div>
<div class ="history3">
    <li><a href="crew_got.php" class="abox">Deduct product</a></li>
</div>
</body>
</html>
