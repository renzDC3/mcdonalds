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

// Define products for both tables
$products = [
    "clamshell" => [
        ["name" => "1PC", "code" => 2967, "quantity" => isset($_POST["clamshell"]["quantity_1pc"]) ? $_POST["clamshell"]["quantity_1pc"] : 0],
        ["name" => "2PC", "code" => 2987, "quantity" => isset($_POST["clamshell"]["quantity_2pc"]) ? $_POST["clamshell"]["quantity_2pc"] : 0],
        ["name" => "Spaghetti", "code" => 2968, "quantity" => isset($_POST["clamshell"]["quantity_spaghetti"]) ? $_POST["clamshell"]["quantity_spaghetti"] : 0],
        ["name" => "Fillet", "code" => 2957, "quantity" => isset($_POST["clamshell"]["quantity_fillet"]) ? $_POST["clamshell"]["quantity_fillet"] : 0],
    ],
    "can" => [
        ["name" => "Fizz", "code" => 5924, "quantity" => isset($_POST["can"]["quantity_mcfizz"]) ? $_POST["can"]["quantity_mcfizz"] : 0],
        ["name" => "Soda", "code" => 5479, "quantity" => isset($_POST["can"]["quantity_soda"]) ? $_POST["can"]["quantity_soda"] : 0],
        ["name" => "Cola", "code" => 5482, "quantity" => isset($_POST["can"]["quantity_mccola"]) ? $_POST["can"]["quantity_mccola"] : 0],
        ["name" => "Sprite", "code" => 5447, "quantity" => isset($_POST["can"]["quantity_sprite"]) ? $_POST["can"]["quantity_sprite"] : 0],
    ],
    "powder" => [
        ["name" => "BBQ", "code" => 7890, "quantity" => isset($_POST["powder"]["quantity_BBQ"]) ? $_POST["powder"]["quantity_BBQ"] : 0],
        ["name" => "Cheese", "code" => 7653, "quantity" => isset($_POST["powder"]["quantity_chess"]) ? $_POST["powder"]["quantity_chess"] : 0],
        ["name" => "Breader", "code" => 7554, "quantity" => isset($_POST["powder"]["quantity_breader"]) ? $_POST["powder"]["quantity_breader"] : 0],
        ["name" => "coating", "code" => 7542, "quantity" => isset($_POST["powder"]["quantity_coating"]) ? $_POST["powder"]["quantity_coating"] : 0],
    ],
    "cups" => [
        ["name" => "Mcflurry", "code" => 5799, "quantity" => isset($_POST["cups"]["quantity_Mcflurry"]) ? $_POST["cups"]["quantity_Mcflurry"] : 0],
        ["name" => "Sundae", "code" => 5435, "quantity" => isset($_POST["cups"]["quantity_Sundae"]) ? $_POST["cups"]["quantity_Sundae"] : 0],
        ["name" => "12oz", "code" => 5684, "quantity" => isset($_POST["cups"]["quantity_12oz"]) ? $_POST["cups"]["quantity_12oz"] : 0],
        ["name" => "16oz", "code" => 5788, "quantity" => isset($_POST["cups"]["quantity_16oz"]) ? $_POST["cups"]["quantity_16oz"] : 0],
    ],
    "sauces" => [
        ["name" => "BBQs", "code" => 8092, "quantity" => isset($_POST["sauces"]["quantity_bbqs"]) ? $_POST["sauces"]["quantity_bbqs"] : 0],
        ["name" => "sweet", "code" => 8322, "quantity" => isset($_POST["sauces"]["quantity_sweet"]) ? $_POST["sauces"]["quantity_sweet"] : 0],
        ["name" => "maple", "code" => 8463, "quantity" => isset($_POST["sauces"]["quantity_maple"]) ? $_POST["sauces"]["quantity_maple"] : 0],
        ["name" => "syrups", "code" => 8657, "quantity" => isset($_POST["sauces"]["quantity_syrups"]) ? $_POST["sauces"]["quantity_syrups"] : 0],
    ],
    "paperbag" => [
        ["name" => "A", "code" => 8092, "quantity" => isset($_POST["paperbag"]["quantity_A"]) ? $_POST["paperbag"]["quantity_A"] : 0],
        ["name" => "B", "code" => 8322, "quantity" => isset($_POST["paperbag"]["quantity_B"]) ? $_POST["paperbag"]["quantity_B"] : 0],
        ["name" => "C", "code" => 8463, "quantity" => isset($_POST["paperbag"]["quantity_C"]) ? $_POST["paperbag"]["quantity_C"] : 0],
        ["name" => "D", "code" => 8657, "quantity" => isset($_POST["paperbag"]["quantity_D"]) ? $_POST["paperbag"]["quantity_D"] : 0],
    ],
    "lids" => [
        ["name" => "12oz", "code" => 9542, "quantity" => isset($_POST["lids"]["quantity_12oz"]) ? $_POST["lids"]["quantity_12oz"] : 0],
        ["name" => "16oz", "code" => 9422, "quantity" => isset($_POST["lids"]["quantity_6oz"]) ? $_POST["lids"]["quantity_6oz"] : 0],
        ["name" => "dome", "code" => 9533, "quantity" => isset($_POST["lids"]["quantity_dome"]) ? $_POST["lids"]["quantity_dome"] : 0],
        ["name" => "coffee", "code" => 9267, "quantity" => isset($_POST["lids"]["quantity_coffee"]) ? $_POST["lids"]["quantity_coffee"] : 0],
    ],
    "utensil" => [
        ["name" => "spoon", "code" => 3992, "quantity" => isset($_POST["utensil"]["quantity_spoon"]) ? $_POST["utensil"]["quantity_spoon"] : 0],
        ["name" => "knife", "code" => 3998, "quantity" => isset($_POST["utensil"]["quantity_knife"]) ? $_POST["utensil"]["quantity_knife"] : 0],
        ["name" => "teaspoon", "code" => 2712, "quantity" => isset($_POST["utensil"]["quantity_teaspoon"]) ? $_POST["utensil"]["quantity_teaspoon"] : 0],
        ["name" => "fork", "code" => 3532, "quantity" => isset($_POST["utensil"]["quantity_fork"]) ? $_POST["utensil"]["quantity_fork"] : 0],
    ],
    "boxes" => [
        ["name" => "Happy meal Box", "code" => 6434, "quantity" => isset($_POST["boxes"]["quantity_happymeal"]) ? $_POST["boxes"]["quantity_happymeal"] : 0],
        ["name" => "Mcshare box", "code" => 6534, "quantity" => isset($_POST["boxes"]["quantity_mcsharebox"]) ? $_POST["boxes"]["quantity_mcsharebox"] : 0],
       
    ],
    "granules" => [
        ["name" => "ice coffee", "code" => 3993, "quantity" => isset($_POST["granules"]["quantity_iceCoffe"]) ? $_POST["granules"]["quantity_iceCoffe"] : 0],
        ["name" => "brewed coffee", "code" => 3999, "quantity" => isset($_POST["granules"]["quantity_brewedCoffe"]) ? $_POST["granules"]["quantity_brewedCoffe"] : 0],
       
    ],
    "tissues" => [
        ["name" => "kitchen tissue", "code" => 7321, "quantity" => isset($_POST["tissues"]["quantity_kitchenTissues"]) ? $_POST["tissues"]["quantity_kitchenTissues"] : 0],
        ["name" => "restroom tissue", "code" => 7753, "quantity" => isset($_POST["tissues"]["quantity_restroomTissues"]) ? $_POST["tissues"]["quantity_restroomTissues"] : 0],
        ["name" => "serving tissue", "code" => 7231, "quantity" => isset($_POST["tissues"]["quantity_servingTissues"]) ? $_POST["tissues"]["quantity_servingTissues"] : 0],
 
    ],
    "drinks" => [
        ["name" => "ice coffee", "code" => 7321, "quantity" => isset($_POST["drinks"]["quantity_iceCoffe"]) ? $_POST["drinks"]["quantity_iceCoffe"] : 0],
        ["name" => "Sprite", "code" => 7753, "quantity" => isset($_POST["drinks"]["quantity_sprite"]) ? $_POST["drinks"]["quantity_sprite"] : 0],
        ["name" => "CocaCola", "code" => 7231, "quantity" => isset($_POST["drinks"]["quantity_CocaCola"]) ? $_POST["drinks"]["quantity_CocaCola"] : 0],
 
    ]
    
];

$alerts = []; // Initialize an array to hold alert messages

// Process each product type
// Process each product type
foreach ($products as $table_name => $product_list) {
    foreach ($product_list as $product) {
        $product_name = $product["name"];
        $quantity = $product["quantity"];
        $code = $product["code"];

        if ($quantity > 0) {
            if ($action === "add") {
                // Update session tracking for added quantities
                $_SESSION['added_quantities'][$table_name][$product_name] = ($_SESSION['added_quantities'][$table_name][$product_name] ?? 0) + intval($quantity);
                
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
                        $alerts[] = "Inefficient quantity of $product_name. Available: $current_quantity.";
                    } else {
                        // Update session tracking for removed quantities only if the quantity is sufficient
                        $_SESSION['removed_quantities'][$table_name][$product_name] = ($_SESSION['removed_quantities'][$table_name][$product_name] ?? 0) + intval($quantity);

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
foreach (['clamshell', 'can', 'powder', 'cups','sauces','paperbag','lids','utensil','boxes','granules','tissues','drinks'] as $table) {
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
