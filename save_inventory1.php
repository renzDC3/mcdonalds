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

$action = $_POST['action'];

if (!isset($_SESSION['added_quantities'])) {
    $_SESSION['added_quantities'] = [];
}
if (!isset($_SESSION['removed_quantities'])) {
    $_SESSION['removed_quantities'] = [];
}

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
        ["name" => "Sprite can", "code" => 5447, "quantity" => isset($_POST["can"]["quantity_sprite"]) ? $_POST["can"]["quantity_sprite"] : 0, "location" => "Row 4,Slot2"],
    ],
    "powder" => [
        ["name" => "BBQ powder", "code" => 7890, "quantity" => isset($_POST["powder"]["quantity_BBQ"]) ? $_POST["powder"]["quantity_BBQ"] : 0, "location" => "Row 1,Slot3"],
        ["name" => "Cheese", "code" => 7653, "quantity" => isset($_POST["powder"]["quantity_chess"]) ? $_POST["powder"]["quantity_chess"] : 0, "location" => "Row 2,Slot3"],
        ["name" => "Breader", "code" => 7554, "quantity" => isset($_POST["powder"]["quantity_breader"]) ? $_POST["powder"]["quantity_breader"] : 0, "location" => "Row 3,Slot3"],
        ["name" => "coating", "code" => 7542, "quantity" => isset($_POST["powder"]["quantity_coating"]) ? $_POST["powder"]["quantity_coating"] : 0, "location" => "Row 4,Slot3"],
    ],
    "cups" => [
        ["name" => "Mcflurry", "code" => 5799, "quantity" => isset($_POST["cups"]["quantity_Mcflurry"]) ? $_POST["cups"]["quantity_Mcflurry"] : 0, "location" => "Row 1,Slot4"],
        ["name" => "Sundae", "code" => 5435, "quantity" => isset($_POST["cups"]["quantity_Sundae"]) ? $_POST["cups"]["quantity_Sundae"] : 0, "location" => "Row 2,Slot4"],
        ["name" => "12oz cups", "code" => 5684, "quantity" => isset($_POST["cups"]["quantity_12oz"]) ? $_POST["cups"]["quantity_12oz"] : 0, "location" => "Row 3,Slot4"],
        ["name" => "16oz cups", "code" => 5788, "quantity" => isset($_POST["cups"]["quantity_16oz"]) ? $_POST["cups"]["quantity_16oz"] : 0, "location" => "Row 4,Slot4"],
    ],
    "sauces" => [
        ["name" => "BBQ sauce", "code" => 8092, "quantity" => isset($_POST["sauces"]["quantity_bbqs"]) ? $_POST["sauces"]["quantity_bbqs"] : 0, "location" => "Row 1,Slot5"],
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
        ["name" => "12oz lids", "code" => 9542, "quantity" => isset($_POST["lids"]["quantity_12oz"]) ? $_POST["lids"]["quantity_12oz"] : 0, "location" => "Row 1,Slot7"],
        ["name" => "16oz lids", "code" => 9422, "quantity" => isset($_POST["lids"]["quantity_16oz"]) ? $_POST["lids"]["quantity_16oz"] : 0, "location" => "Row 2,Slot7"],
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
        ["name" => "Happy meal Box", "code" => 6434, "quantity" => isset($_POST["boxes"]["quantity_happymeal"]) ? $_POST["boxes"]["quantity_happymeal"] : 0, "location" => "Row 1,Slot9"],
        ["name" => "Mcshare box", "code" => 6534, "quantity" => isset($_POST["boxes"]["quantity_mcsharebox"]) ? $_POST["boxes"]["quantity_mcsharebox"] : 0, "location" => "Row 2,Slot9"],
    ],
    "granules" => [
        ["name" => "coffee granules", "code" => 3993, "quantity" => isset($_POST["granules"]["quantity_coffeg"]) ? $_POST["granules"]["quantity_coffeg"] : 0, "location" => "Row 3,Slot9"],
        ["name" => "brewed coffee granules", "code" => 3999, "quantity" => isset($_POST["granules"]["quantity_brewedCoffe"]) ? $_POST["granules"]["quantity_brewedCoffe"] : 0, "location" => "Row 4,Slot9"],
    ],
    "tissues" => [
        ["name" => "kitchen tissue", "code" => 7321, "quantity" => isset($_POST["tissues"]["quantity_kitchenTissues"]) ? $_POST["tissues"]["quantity_kitchenTissues"] : 0, "location" => "Row 1,Slot10"],
        ["name" => "restroom tissue", "code" => 7753, "quantity" => isset($_POST["tissues"]["quantity_restroomTissues"]) ? $_POST["tissues"]["quantity_restroomTissues"] : 0, "location" => "Row 2,Slot10"],
        ["name" => "serving tissue", "code" => 7231, "quantity" => isset($_POST["tissues"]["quantity_servingTissues"]) ? $_POST["tissues"]["quantity_servingTissues"] : 0, "location" => "Row 2,Slot10"],

    ],
    "drinks" => [
        ["name" => "ice coffee", "code" => 6260, "quantity" => isset($_POST["drinks"]["quantity_iceCoffe"]) ? $_POST["drinks"]["quantity_iceCoffe"] : 0, "location" => "Row 3,Slot10"],
        ["name" => "Sprite", "code" => 6259, "quantity" => isset($_POST["drinks"]["quantity_sprite"]) ? $_POST["drinks"]["quantity_sprite"] : 0, "location" => "Row 4,Slot10"],
        ["name" => "CocaCola", "code" => 6257, "quantity" => isset($_POST["drinks"]["quantity_CocaCola"]) ? $_POST["drinks"]["quantity_CocaCola"] : 0, "location" => "Row 5,Slot10"],
    ],
];

$conn->autocommit(FALSE); // Start transaction

function getNextBatch($conn, $productName) {
    $sql = "SELECT COUNT(*) as count FROM received_history WHERE product_name = ? AND action = 'added'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] + 1; // Incrementing the count gives the next batch number
}

try {
    // Assuming $products and $action are defined elsewhere in your code
    foreach ($products as $table_name => $items) {
        foreach ($items as $item) {
            $product_name = $item['name'];
            $code = $item['code'];
            $quantity = isset($item['quantity']) ? intval($item['quantity']) : 0;

            if ($quantity > 0) {
                date_default_timezone_set('Asia/Manila');
                $date_time = date('Y-m-d H:i:s');

                if ($action === "add") {
                    // Remove zero quantity rows before adding
                    $stmt = $conn->prepare("DELETE FROM $table_name WHERE code = ? AND quantity = 0");
                    $stmt->bind_param("i", $code);
                    $stmt->execute();
                    $stmt->close();

                    // Get the next batch number for the product
                    $batch = getNextBatch($conn, $product_name);

                    // Add new quantity
                    $stmt = $conn->prepare("INSERT INTO $table_name (product_name, quantity, code) VALUES (?, ?, ?) 
                         ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
                    $stmt->bind_param("sii", $product_name, $quantity, $code);
                    $stmt->execute();
                    $stmt->close();

                    $_SESSION['added_quantities'][$table_name][$product_name] = ($_SESSION['added_quantities'][$table_name][$product_name] ?? 0) + $quantity;

                    // Insert into received_history with batch
                    $stmt = $conn->prepare("INSERT INTO received_history (product_name, code, quantity, date_time, action, batch) VALUES (?, ?, ?, ?, 'added', ?)");
                    $stmt->bind_param("ssisi", $product_name, $code, $quantity, $date_time, $batch);
                    $stmt->execute();
                    $stmt->close();
                } elseif ($action === "remove") {
                    // Check current total quantity
                    $stmt = $conn->prepare("SELECT SUM(quantity) as total_quantity FROM $table_name WHERE code = ?");
                    $stmt->bind_param("i", $code);
                    $stmt->execute();
                    $stmt->bind_result($current_total_quantity);
                    $stmt->fetch();
                    $stmt->close();

                    if ($current_total_quantity !== null && $current_total_quantity >= $quantity) {
                        // Retrieve all rows with the given code, ordered by quantity
                        $stmt = $conn->prepare("SELECT id, quantity FROM $table_name WHERE code = ? ORDER BY quantity DESC");
                        $stmt->bind_param("i", $code);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $remaining_quantity_to_remove = $quantity;

                        while ($row = $result->fetch_assoc()) {
                            if ($remaining_quantity_to_remove <= 0) {
                                break;
                            }

                            $row_id = $row['id'];
                            $row_quantity = $row['quantity'];

                            if ($row_quantity > $remaining_quantity_to_remove) {
                                // Update the row quantity
                                $stmt_update = $conn->prepare("UPDATE $table_name SET quantity = quantity - ? WHERE id = ?");
                                $stmt_update->bind_param("ii", $remaining_quantity_to_remove, $row_id);
                                $stmt_update->execute();
                                $stmt_update->close();
                                $remaining_quantity_to_remove = 0;
                            } else {
                                // Remove the entire row quantity
                                $stmt_delete = $conn->prepare("DELETE FROM $table_name WHERE id = ?");
                                $stmt_delete->bind_param("i", $row_id);
                                $stmt_delete->execute();
                                $stmt_delete->close();
                                $remaining_quantity_to_remove -= $row_quantity;
                            }
                        }

                        $stmt->close();

                        $_SESSION['removed_quantities'][$table_name][$product_name] = ($_SESSION['removed_quantities'][$table_name][$product_name] ?? 0) + $quantity;

                        $stmt = $conn->prepare("INSERT INTO received_history (product_name, code, quantity, date_time, action) VALUES (?, ?, ?, ?, 'removed')");
                        $stmt->bind_param("ssds", $product_name, $code, $quantity, $date_time);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        throw new Exception("Not enough quantity to remove for product $product_name with code $code.");
                    }
                }
            }
        }
    }
    $conn->commit(); // Commit transaction
} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction if something went wrong
    echo "Error: " . $e->getMessage();
}

$conn->close();

header("Location: inventory1.php");
exit();
?>