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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $code = $_POST['code'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM received_history WHERE product_name = ? AND code = ? AND action = 'added'");
    $stmt->bind_param("ss", $product_name, $code);
    
    if ($stmt->execute()) {
        // Success message
        $_SESSION['message'] = "Expired product $product_name removed successfully.";
    } else {
        // Error message
        $_SESSION['message'] = "Error removing product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: index.php"); // Redirect back to index.php
exit();
?>
