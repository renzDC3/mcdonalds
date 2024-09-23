<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /mcdonalds.php");
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

$action = $_POST['action'];

// Define products for both tables
$products = [
    "clamshell" => [
        ["name" => "1PC", "code" => 2967, "quantity" => isset($_POST["clamshell"]["quantity_1pc"]) ? $_POST["clamshell"]["quantity_1pc"] : 0],
        ["name" => "2PC", "code" => 2987, "quantity" => isset($_POST["clamshell"]["quantity_2pc"]) ? $_POST["clamshell"]["quantity_2pc"] : 0],
        ["name" => "Spaghetti", "code" => 2968, "quantity" => isset($_POST["clamshell"]["quantity_spaghetti"]) ? $_POST["clamshell"]["quantity_spaghetti"] : 0],
        ["name" => "Fillet", "code" => 2957, "quantity" => isset($_POST["clamshell"]["quantity_fillet"]) ? $_POST["clamshell"]["quantity_fillet"] : 0],
    ],
    "can" => [
        ["name" => "McFizz", "code" => 5924, "quantity" => isset($_POST["can"]["quantity_mcfizz"]) ? $_POST["can"]["quantity_mcfizz"] : 0],
        ["name" => "Soda", "code" => 5479, "quantity" => isset($_POST["can"]["quantity_soda"]) ? $_POST["can"]["quantity_soda"] : 0],
        ["name" => "McCola", "code" => 5482, "quantity" => isset($_POST["can"]["quantity_mccola"]) ? $_POST["can"]["quantity_mccola"] : 0],
        ["name" => "Sprite", "code" => 5447, "quantity" => isset($_POST["can"]["quantity_sprite"]) ? $_POST["can"]["quantity_sprite"] : 0],
    ],
    "powder" => [
        ["name" => "Seasoning", "code" => 7890, "quantity" => isset($_POST["powder"]["quantity_seasoning"]) ? $_POST["powder"]["quantity_seasoning"] : 0],
        ["name" => "SpiceMix", "code" => 7653, "quantity" => isset($_POST["powder"]["quantity_spicemix"]) ? $_POST["powder"]["quantity_spicemix"] : 0],
        ["name" => "FryCoating", "code" => 7554, "quantity" => isset($_POST["powder"]["quantity_frycoating"]) ? $_POST["powder"]["quantity_frycoating"] : 0],
        ["name" => "ShakeMix", "code" => 7542, "quantity" => isset($_POST["powder"]["quantity_shakemix"]) ? $_POST["powder"]["quantity_shakemix"] : 0],
    ]
];

$alerts = []; // Initialize an array to hold alert messages

// Process each product type
foreach ($products as $table_name => $product_list) {
    foreach ($product_list as $product) {
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

                    $stmt->close();
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
                    if ($current_quantity - $quantity < 0) {
                        $alerts[] = "Inefficient quantity of $product_name.  Available: $current_quantity.";
                    } else {
                        $stmt = $conn->prepare("UPDATE $table_name SET quantity = quantity - ? WHERE product_name = ? AND code = ?");
                        $stmt->bind_param("isi", $quantity, $product_name, $code);
                    }
                }
            }

            // Execute the prepared statement if it's set
            if (isset($stmt)) {
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// Store alerts in the session if there are any
if (!empty($alerts)) {
    $_SESSION['alerts'] = $alerts;
}

// Fetch the latest data for both tables
$latest_data = [];
foreach (["clamshell", "can", "powder"] as $table) {
    $result = $conn->query("SELECT product_name, SUM(quantity) as total_quantity, code FROM $table GROUP BY product_name, code");

    while ($row = $result->fetch_assoc()) {
        $latest_data[$table][] = $row;
    }
}

$conn->close();

$_SESSION['latest_data'] = $latest_data;
header("Location: /mcdonalds/inventory.php");
exit();
?>
