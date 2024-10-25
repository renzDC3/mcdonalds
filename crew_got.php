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

// Fetch the removal history
$product_history = [];
$stmt = $conn->prepare("SELECT product_name, quantity, date_time FROM received_history WHERE action = 'removed' ORDER BY date_time DESC");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $product_history[] = $row;
}

$stmt->close();

// Handle archiving
// Handle archiving
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive'])) {
    // Archive records
    $archive_sql = "
        INSERT INTO crewgot_archived (product_name, quantity, date_time, action)
        SELECT product_name, quantity, date_time, action
        FROM received_history
        WHERE action = 'removed'";

    if ($conn->query($archive_sql) === TRUE) {
        // Delete archived records only if the archiving was successful
        $delete_sql = "DELETE FROM received_history WHERE action = 'removed'";
        if ($conn->query($delete_sql) === TRUE) {
            $archive_message = "Records successfully archived and removed.";
        } else {
            $archive_message = "Error deleting records: " . $conn->error;
        }
    } else {
        $archive_message = "Error archiving records: " . $conn->error;
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <script src="loadLayout3.js" defer></script>
    <link rel="stylesheet" href="history_table.css">
    <title>Crew Got</title>
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
        width: 120px;
        background-color:black;
        color:white;
        text-decoration:none;
    }
    #myframe{
        margin-top:147px;
        position:absolute;
        border-radius: 5px;
        overflow: scroll;
        width: 290px;
        height:200px;
        z-index:1;
        visibility: hidden; /* Start hidden but keep space */
        border: none; /* Optional: remove border */
    }
</style>
<body>

<h4>Deduct product History</h4>
    <form method="post" action="crew_got.php">
        <button type="submit" name="archive" class="buttonarchived">Archive</button>
    </form>
    <a class="button" onclick="toggleFrame()">Open Archived</a>
<iframe id="myframe"></iframe>

<script>
function toggleFrame() {
    var frame = document.getElementById("myframe");
    if (frame.style.visibility === "hidden" || frame.style.visibility === "") {
        frame.src = "crewgot_archived.php"; // Set the source only when opening
        frame.style.visibility = "visible"; // Show the iframe
    } else {
        frame.style.visibility = "hidden"; // Hide the iframe without removing space
        frame.src = ""; // Optionally clear the source when hiding
    }
}
</script>


<div class ="history1">
    <li><a href="history.php" class="abox">Restock product</a></li>
</div>
<div class ="history2">
    <li><a href="user_log.php" class="abox">User Log</a></li>
</div>
<div class ="history3">
    <li><a href="crew_got.php" class="abox">Deduct product</a></li>
</div>



    <table class="table-d">
        <thead>
            <tr>
                <td class="td1">Product Name</td>
                <td class="td1">Boxes Get</td>
                <td class="td1">Date & Time</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product_history as $entry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($entry['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($entry['date_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
