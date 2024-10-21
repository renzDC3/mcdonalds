<?php
session_start();
if (!isset($_SESSION['manager_loggedin'])) {
    header('Location: login_manager.php');
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $newExpirationDate = $_POST['new_expiration_date'];
    $productCode = $_POST['product_code'];

    // Validate input
    if (!empty($productName) && !empty($newExpirationDate) && !empty($productCode)) {
        // Prepare and execute the update query
        $stmt = $conn->prepare("UPDATE received_history SET expiration_date = ? WHERE product_name = ? AND code = ?");
        $stmt->bind_param("sss", $newExpirationDate, $productName, $productCode);
        
        if ($stmt->execute()) {
            // Redirect to product_expiring.php after successful update
            header('Location: product_expiring.php');
            exit; // Ensure no further code is executed
        } else {
            echo "Error updating expiration date: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>
