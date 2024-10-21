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
    $quantity = $_POST['quantity'];
    $code = $_POST['code'];
    
    // Validate input
    if (!empty($productName) && !empty($quantity) && !empty($code)) {
        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM received_history WHERE product_name = ? AND code = ? LIMIT ?");
        $stmt->bind_param("ssi", $productName, $code, $quantity); // Assuming quantity is used as limit

        if ($stmt->execute()) {
            // Redirect to the page where the form is rendered
            header('Location: index2.php'); // Adjust this to your actual page
            exit;
        } else {
            echo "Error removing product: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Please provide valid input.";
    }
}

$conn->close();
?>
