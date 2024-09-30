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

// Initialize session variables for tracking quantities if not already set
if (!isset($_SESSION['added_quantities'])) {
    $_SESSION['added_quantities'] = [];
}
if (!isset($_SESSION['removed_quantities'])) {
    $_SESSION['removed_quantities'] = [];
}

// Define products for both tables with locations
$products = [
    "clamshell" => [
        ["name" => "1PC", "code" => 2967, "quantity" => isset($_POST["clamshell"]["quantity_1pc"]) ? $_POST["clamshell"]["quantity_1pc"] : 0, "location" => "Row 1,Slot1"],
        ["name" => "2PC", "code" => 2987, "quantity" => isset($_POST["clamshell"]["quantity_2pc"]) ? $_POST["clamshell"]["quantity_2pc"] : 0, "location" => "Row 2,Slot1"],
        ["name" => "Spaghetti", "code" => 2968, "quantity" => isset($_POST["clamshell"]["quantity_spaghetti"]) ? $_POST["clamshell"]["quantity_spaghetti"] : 0, "location" => "Row 3,Slot1"],
        ["name" => "Fillet", "code" => 2957, "quantity" => isset($_POST["clamshell"]["quantity_fillet"]) ? $_POST["clamshell"]["quantity_fillet"] : 0, "location" => "Row 4,Slot1"],
    ],
    "can" => [
        ["name" => "Fizz", "code" => 5924, "quantity" => isset($_POST["can"]["quantity_fizz"]) ? $_POST["can"]["quantity_fizz"] : 0, "location" => "Row 1,Slot2"],
        ["name" => "Soda", "code" => 5479, "quantity" => isset($_POST["can"]["quantity_soda"]) ? $_POST["can"]["quantity_soda"] : 0, "location" => "Row 2,Slot2"],
        ["name" => "Cola", "code" => 5482, "quantity" => isset($_POST["can"]["quantity_cola"]) ? $_POST["can"]["quantity_cola"] : 0, "location" => "Row 3,Slot2"],
        ["name" => "Sprite", "code" => 5447, "quantity" => isset($_POST["can"]["quantity_sprite"]) ? $_POST["can"]["quantity_sprite"] : 0, "location" => "Row 4,Slot2"],
    ],
    "powder" => [
        ["name" => "BBQ", "code" => 7890, "quantity" => isset($_POST["powder"]["quantity_BBQ"]) ? $_POST["powder"]["quantity_BBQ"] : 0, "location" => "Row 1,Slot3"],
        ["name" => "Cheese", "code" => 7653, "quantity" => isset($_POST["powder"]["quantity_chess"]) ? $_POST["powder"]["quantity_chess"] : 0, "location" => "Row 2,Slot3"],
        ["name" => "Breader", "code" => 7554, "quantity" => isset($_POST["powder"]["quantity_breader"]) ? $_POST["powder"]["quantity_breader"] : 0, "location" => "Row 3,Slot3"],
        ["name" => "coating", "code" => 7542, "quantity" => isset($_POST["powder"]["quantity_coating"]) ? $_POST["powder"]["quantity_coating"] : 0, "location" => "Row 4,Slot3"],
    ],
    "cups" => [
        ["name" => "Mcflurry", "code" => 5799, "quantity" => isset($_POST["cups"]["quantity_Mcflurry"]) ? $_POST["cups"]["quantity_Mcflurry"] : 0, "location" => "Row 1,Slot4"],
        ["name" => "Sundae", "code" => 5435, "quantity" => isset($_POST["cups"]["quantity_Sundae"]) ? $_POST["cups"]["quantity_Sundae"] : 0, "location" => "Row 2,Slot4"],
        ["name" => "12oz", "code" => 5684, "quantity" => isset($_POST["cups"]["quantity_12oz"]) ? $_POST["cups"]["quantity_12oz"] : 0, "location" => "Row 3,Slot4"],
        ["name" => "16oz", "code" => 5788, "quantity" => isset($_POST["cups"]["quantity_16oz"]) ? $_POST["cups"]["quantity_16oz"] : 0, "location" => "Row 4,Slot4"],
    ],
    "sauces" => [
        ["name" => "BBQs", "code" => 8092, "quantity" => isset($_POST["sauces"]["quantity_bbqs"]) ? $_POST["sauces"]["quantity_bbqs"] : 0, "location" => "Row 1,Slot5"],
        ["name" => "sweet", "code" => 8322, "quantity" => isset($_POST["sauces"]["quantity_sweet"]) ? $_POST["sauces"]["quantity_sweet"] : 0, "location" => "Row 2,Slot5"],
        ["name" => "maple", "code" => 8463, "quantity" => isset($_POST["sauces"]["quantity_maple"]) ? $_POST["sauces"]["quantity_maple"] : 0, "location" => "Row 3,Slot5"],
        ["name" => "syrups", "code" => 8657, "quantity" => isset($_POST["sauces"]["quantity_syrups"]) ? $_POST["sauces"]["quantity_syrups"] : 0, "location" => "Row 4,Slot5"],
    ],
    "paperbag" => [
        ["name" => "A", "code" => 8092, "quantity" => isset($_POST["paperbag"]["quantity_A"]) ? $_POST["paperbag"]["quantity_A"] : 0, "location" => "Row 1,Slot6"],
        ["name" => "B", "code" => 8322, "quantity" => isset($_POST["paperbag"]["quantity_B"]) ? $_POST["paperbag"]["quantity_B"] : 0, "location" => "Row 2,Slot6"],
        ["name" => "C", "code" => 8463, "quantity" => isset($_POST["paperbag"]["quantity_C"]) ? $_POST["paperbag"]["quantity_C"] : 0, "location" => "Row 3,Slot6"],
        ["name" => "D", "code" => 8657, "quantity" => isset($_POST["paperbag"]["quantity_D"]) ? $_POST["paperbag"]["quantity_D"] : 0, "location" => "Row 4,Slot6"],
    ],
    "lids" => [
        ["name" => "12oz", "code" => 9542, "quantity" => isset($_POST["lids"]["quantity_12oz"]) ? $_POST["lids"]["quantity_12oz"] : 0, "location" => "Row 1,Slot7"],
        ["name" => "16oz", "code" => 9422, "quantity" => isset($_POST["lids"]["quantity_6oz"]) ? $_POST["lids"]["quantity_6oz"] : 0, "location" => "Row 2,Slot7"],
        ["name" => "dome", "code" => 9533, "quantity" => isset($_POST["lids"]["quantity_dome"]) ? $_POST["lids"]["quantity_dome"] : 0, "location" => "Row 3,Slot7"],
        ["name" => "coffee", "code" => 9267, "quantity" => isset($_POST["lids"]["quantity_coffee"]) ? $_POST["lids"]["quantity_coffee"] : 0, "location" => "Row 4,Slot7"],
    ],
    "utensil" => [
        ["name" => "spoon", "code" => 3992, "quantity" => isset($_POST["utensil"]["quantity_spoon"]) ? $_POST["utensil"]["quantity_spoon"] : 0, "location" => "Row 1,Slot8"],
        ["name" => "knife", "code" => 3998, "quantity" => isset($_POST["utensil"]["quantity_knife"]) ? $_POST["utensil"]["quantity_knife"] : 0, "location" => "Row 2,Slot8"],
        ["name" => "teaspoon", "code" => 2712, "quantity" => isset($_POST["utensil"]["quantity_teaspoon"]) ? $_POST["utensil"]["quantity_teaspoon"] : 0, "location" => "Row 3,Slot8"],
        ["name" => "fork", "code" => 3532, "quantity" => isset($_POST["utensil"]["quantity_fork"]) ? $_POST["utensil"]["quantity_fork"] : 0, "location" => "Row 4,Slot8"],
    ],
    "boxes" => [
        ["name" => "small", "code" => 7473, "quantity" => isset($_POST["boxes"]["quantity_small"]) ? $_POST["boxes"]["quantity_small"] : 0, "location" => "Row 1,Slot9"],
        ["name" => "large", "code" => 7534, "quantity" => isset($_POST["boxes"]["quantity_large"]) ? $_POST["boxes"]["quantity_large"] : 0, "location" => "Row 2,Slot9"],
        ["name" => "extra large", "code" => 7543, "quantity" => isset($_POST["boxes"]["quantity_extra_large"]) ? $_POST["boxes"]["quantity_extra_large"] : 0, "location" => "Row 3,Slot9"],
        ["name" => "family pack", "code" => 7549, "quantity" => isset($_POST["boxes"]["quantity_family_pack"]) ? $_POST["boxes"]["quantity_family_pack"] : 0, "location" => "Row 4,Slot9"],
    ],
    "granules" => [
        ["name" => "sugar", "code" => 7589, "quantity" => isset($_POST["granules"]["quantity_sugar"]) ? $_POST["granules"]["quantity_sugar"] : 0, "location" => "Row 3,Slot9"],
        ["name" => "salt", "code" => 7590, "quantity" => isset($_POST["granules"]["quantity_salt"]) ? $_POST["granules"]["quantity_salt"] : 0, "location" => "Row 4,Slot9"],
    ],
    "tissues" => [
        ["name" => "tissue", "code" => 9153, "quantity" => isset($_POST["tissues"]["quantity_tissue"]) ? $_POST["tissues"]["quantity_tissue"] : 0, "location" => "Row 1,Slot10"],
        ["name" => "napkin", "code" => 9154, "quantity" => isset($_POST["tissues"]["quantity_napkin"]) ? $_POST["tissues"]["quantity_napkin"] : 0, "location" => "Row 2,Slot10"],
    ],
    "drinks" => [
        ["name" => "soda", "code" => 9155, "quantity" => isset($_POST["drinks"]["quantity_soda"]) ? $_POST["drinks"]["quantity_soda"] : 0, "location" => "Row 3,Slot10"],
        ["name" => "juice", "code" => 9156, "quantity" => isset($_POST["drinks"]["quantity_juice"]) ? $_POST["drinks"]["quantity_juice"] : 0, "location" => "Row 4,Slot10"],
        ["name" => "water", "code" => 9157, "quantity" => isset($_POST["drinks"]["quantity_water"]) ? $_POST["drinks"]["quantity_water"] : 0, "location" => "Row 5,Slot10"],
    ],
];

// Continue with saving the inventory as before...


// Process each product and perform the required actions
foreach ($products as $table_name => $items) {
    foreach ($items as $item) {
        $product_name = $item['name'];
        $code = $item['code'];
        $quantity = isset($item['quantity']) ? intval($item['quantity']) : 0; // Ensure quantity is an integer

        if ($quantity > 0) {
            // Handle the action for added quantities
            if ($action === "add") {
                $stmt = $conn->prepare("INSERT INTO $table_name (product_name, quantity, code) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
                $stmt->bind_param("sii", $product_name, $quantity, $code);
                $stmt->execute();
                $stmt->close();

                // Track added quantities
                $_SESSION['added_quantities'][$table_name][$product_name] = ($_SESSION['added_quantities'][$table_name][$product_name] ?? 0) + $quantity;
                
                // Insert into received_history
                date_default_timezone_set('Asia/Manila');
                $date_time = date('Y-m-d H:i:sa');
                $category = $table_name;

                $stmt = $conn->prepare("INSERT INTO received_history (product_name, code, quantity, date_time, category) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siiss", $product_name, $code, $quantity, $date_time, $category);
                $stmt->execute();
                $stmt->close();
            } 
            // Handle the action for removed quantities
            elseif ($action === "remove") {
                // Check current quantity before removing
                $stmt = $conn->prepare("SELECT quantity FROM $table_name WHERE product_name = ? AND code = ?");
                $stmt->bind_param("si", $product_name, $code);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $current_quantity = intval($row['quantity']); // Ensure current_quantity is an integer

                    // Prevent negative quantity
                    if ($current_quantity - $quantity < 0) {
                        $alerts[] = "Inefficient quantity of $product_name. Available: $current_quantity.";
                    } else {
                        // Update session tracking for removed quantities only if the quantity is sufficient
                        $_SESSION['removed_quantities'][$table_name][$product_name] = ($_SESSION['removed_quantities'][$table_name][$product_name] ?? 0) + $quantity;

                        // Update the inventory
                        $stmt = $conn->prepare("UPDATE $table_name SET quantity = quantity - ? WHERE product_name = ? AND code = ?");
                        $stmt->bind_param("isi", $quantity, $product_name, $code);
                        $stmt->execute(); // Execute the update statement
                        $stmt->close();

                        // Log the product removal in product_history
                        date_default_timezone_set('Asia/Manila');
                        $date_time = date('Y-m-d H:i:sa'); // Get current date and time
                        $category = $table_name; // Set category to the current table name

                        // Insert the product getting record into product_history
                        $stmt = $conn->prepare("INSERT INTO product_history (product_name, quantity, date_time, category) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("siss", $product_name, $quantity, $date_time, $category);
                        $stmt->execute();
                        $stmt->close();
                    }
                } else {
                    $alerts[] = "Product not found in $table_name.";
                }
            }
        }
    }
}

// Redirect back to inventory page after processing
header("Location:/mcdonalds/inventory.php");
exit();
?>
