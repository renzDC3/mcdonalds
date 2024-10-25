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
    // Ensure all arrays are set
    if (isset($_POST['expiration_date']) && isset($_POST['product_code']) && isset($_POST['product_name'])) {
        $expiration_dates = $_POST['expiration_date'];
        $product_codes = $_POST['product_code'];
        $product_names = $_POST['product_name'];

        // Loop through each product to update the expiration date
        foreach ($expiration_dates as $index => $newExpirationDate) {
            $productCode = $product_codes[$index];
            $productName = $product_names[$index];

            // Validate input
            if (!empty($productName) && !empty($newExpirationDate) && !empty($productCode)) {
                // Prepare and execute the update query
                $stmt = $conn->prepare("UPDATE received_history SET expiration_date = ? WHERE product_name = ? AND code = ?");
                $stmt->bind_param("sss", $newExpirationDate, $productName, $productCode);
                
                if ($stmt->execute()) {
                    // Optional: You can handle success messages if needed
                } else {
                    echo "Error updating expiration date for {$productName}: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Please fill in all fields.";
                exit; // Exit if validation fails
            }
        }

        // Redirect after successful updates
        header('Location: product_expiring.php');
        exit; // Ensure no further code is executed
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>
