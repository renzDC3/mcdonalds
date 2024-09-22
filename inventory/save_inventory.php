<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /mcdonalds/inventory.php");
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

$table_name = $_POST['table_name'];
$action = $_POST['action'];

$products = [
    ["name" => "1PC", "code" => 2967, "quantity" => isset($_POST["quantity_1pc"]) ? $_POST["quantity_1pc"] : 0],
    ["name" => "2PC", "code" => 2987, "quantity" => isset($_POST["quantity_2pc"]) ? $_POST["quantity_2pc"] : 0],
    ["name" => "Spaghetti", "code" => 2968, "quantity" => isset($_POST["quantity_spaghetti"]) ? $_POST["quantity_spaghetti"] : 0],
    ["name" => "Fillet", "code" => 2957, "quantity" => isset($_POST["quantity_fillet"]) ? $_POST["quantity_fillet"] : 0]
];

$stmt = null; // Initialize $stmt

foreach ($products as $product) {
    $product_name = $product["name"];
    $quantity = $product["quantity"];
    $code = $product["code"];

    if ($quantity > 0) {
        if ($action === "add") {
            // Check if the product already exists
            $stmt = $conn->prepare("SELECT quantity FROM $table_name WHERE product_name = ? AND code = ?");
            $stmt->bind_param("si", $product_name, $code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Product exists, update the quantity
                $row = $result->fetch_assoc();
                $new_quantity = $row['quantity'] + $quantity;

                $stmt->close(); // Close the previous statement
                $stmt = $conn->prepare("UPDATE $table_name SET quantity = ? WHERE product_name = ? AND code = ?");
                $stmt->bind_param("isi", $new_quantity, $product_name, $code);
            } else {
                // Product does not exist, insert a new record
                $stmt = $conn->prepare("INSERT INTO $table_name (product_name, quantity, code) VALUES (?, ?, ?)");
                $stmt->bind_param("sii", $product_name, $quantity, $code);
            }
        } elseif ($action === "remove") {
            // Check current quantity before removing
            $stmt = $conn->prepare("SELECT quantity FROM $table_name WHERE product_name = ? AND code = ?");
            $stmt->bind_param("si", $product_name, $code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $current_quantity = $row['quantity'];

                // Prevent negative quantity
                if ($current_quantity - $quantity >= 0) {
                    $stmt = $conn->prepare("UPDATE $table_name SET quantity = quantity - ? WHERE product_name = ? AND code = ?");
                    $stmt->bind_param("isi", $quantity, $product_name, $code);
                } else {
                    // Handle case where removal would cause negative quantity
                    error_log("Attempted to remove more than available for $product_name. Current: $current_quantity, Attempted: $quantiyt.");
                }
            }
        }

        // Execute the prepared statement if it's set
        if ($stmt) {
            $stmt->execute();
            $stmt->close(); // Close the statement after execution
        }
    }
}

// Fetch the latest data
$result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table_name GROUP BY product_name, code");

$latest_data = [];
while ($row = $result->fetch_assoc()) {
    $latest_data[] = $row;
}

$conn->close();

$_SESSION['latest_data'] = $latest_data;
header("Location: /mcdonalds/inventory/clamshell.php");
exit();

?>
