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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive'])) {
    $archive_sql = "
        INSERT INTO userLog_archived (full_name, crew_number, date_time)
        SELECT full_name, crew_number, date_time
        FROM user_log";

    if ($conn->query($archive_sql) === TRUE) {
        $delete_sql = "DELETE FROM user_log";
        if ($conn->query($delete_sql) === TRUE) {
            $archive_message = "Records successfully archived.";
        } else {
            $archive_message = "Error deleting records: " . $conn->error;
        }
    } else {
        $archive_message = "Error archiving records: " . $conn->error;
    }
}

$sql = "SELECT * FROM user_log ORDER BY date_time DESC";
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
    <title>User Log</title>
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
        width: 374px;
        height:200px;
        z-index:1;
        visibility: hidden; /* Start hidden but keep space */
        border: none; /* Optional: remove border */
       
    }
</style>
<body>
<h3 class="my-4">User Log</h3>

    <form method="post" action="user_log.php">
        <button type="submit" name="archive" class="buttonarchived">Archive</button>
    </form>
    <a class="button" onclick="toggleFrame()">Open Archived</a>
<iframe id="myframe"></iframe>

<script>
function toggleFrame() {
    var frame = document.getElementById("myframe");
    if (frame.style.visibility === "hidden" || frame.style.visibility === "") {
        frame.src = "userLog_archived.php"; // Set the source only when opening
        frame.style.visibility = "visible"; // Show the iframe
    } else {
        frame.style.visibility = "hidden"; // Hide the iframe without removing space
        frame.src = ""; // Optionally clear the source when hiding
    }
}
</script>


    <table class="table-d" id="user_log">
        <thead>
            <tr>
                <td class="td1">Name</td>
                <td class="td1">Crew Number</td>
                <td class="td1">Date & Time</td>
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
