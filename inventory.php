<?php
session_start(); 
if (!isset($_SESSION["user"])) {
   header("Location: index.php");
   exit();
}

$latest_data = isset($_SESSION['latest_data']) ? $_SESSION['latest_data'] : [];
$alerts = isset($_SESSION['alerts']) ? $_SESSION['alerts'] : [];
unset($_SESSION['alerts']); // Clear alerts after displaying

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get the current batch number
function getBatch($conn, $productName) {
    $sql = "SELECT batch FROM received_history WHERE product_name = ? ORDER BY date_time DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['batch'] : 1; // Return batch number or 1 if no record found
}

// Fetch available quantities for all products from the inventory tables
$available_products = [];
$categories = ['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'];

foreach ($categories as $category) {
    $result = $conn->query("SELECT product_name, SUM(quantity) AS total_quantity FROM $category GROUP BY product_name");
    while ($row = $result->fetch_assoc()) {
        $available_products[$category][$row['product_name']] = $row['total_quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="inventory.css">
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="loadLayout.js" defer></script>
    <title>Inventory</title>
</head>
<body>
    <!-- Display alerts if any -->
    <?php if (!empty($alerts)): ?>
        <div class="alert alert-danger">
            <?php foreach ($alerts as $alert): ?>
                <p><?php echo htmlspecialchars($alert); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <table id="history">
    <tr class="tr1">
        <td class="td1">Product Name</td>
        <td class="td1">Restock</td>
        <td class="td1">Deduct</td>
        <td class="td1">Available Products</td>
        <td class="td1">Batch</td>
    </tr>
    <?php 
    foreach ($categories as $category) {
        // Combine both added and removed quantities into a single array of product names
        $product_names = array_unique(
            array_merge(
                array_keys($_SESSION['added_quantities'][$category] ?? []),
                array_keys($_SESSION['removed_quantities'][$category] ?? []),
            )
        );

        foreach ($product_names as $product_name) {
            $added = $_SESSION['added_quantities'][$category][$product_name] ?? 0;
            $removed = $_SESSION['removed_quantities'][$category][$product_name] ?? 0;
            $available = $available_products[$category][$product_name] ?? 0; // Get available quantity
            $batch = getBatch($conn, $product_name); // Get the current batch number
            
            echo '<tr>';
            echo '<td>' . htmlspecialchars($product_name) . '</td>';
            echo '<td>' . htmlspecialchars($added) . '</td>';
            echo '<td>' . htmlspecialchars($removed) . '</td>';
            echo '<td>' . htmlspecialchars($available) . '</td>';
            echo '<td>' . htmlspecialchars($batch) . '</td>'; // Display batch number
            echo '</tr>';
        }
    }
    $conn->close();
    ?>
    </table>
    
   
    
    <div class="inputwidth">
    <input style="background-color: rgb(238, 238, 238) ;width:600px; text-align:center;margin-left:420px;" type="text" id="scannerInput" autofocus placeholder="SCAN QR CODE PRODUCT">
    </div>
    



<form action="save_inventory.php" method="POST">


<div class="container">
    <div class="box">
        <h1>CLAMSHELL</h1>

        <table>
            
            <tr>
                <th>Product Name</th>
                <th>Boxes</th>
                <th>Code</th>
            </tr>
            <tr>
            <td>1PC</td>
                <td><input type="number" id="quantity_2967" name="clamshell[quantity_1pc]" min="0" max="200" step="1"></td>
                <td>2967</td>
            </tr>
            <tr>
                <td>2PC</td>
                <td><input type="number" id="quantity_2987" name="clamshell[quantity_2pc]" min="0" max="200" step="1"></td>
                <td>2987</td>
            </tr>
            <tr>
                <td>Spaghetti</td>
                <td><input type="number" id="quantity_2968" name="clamshell[quantity_spaghetti]" min="0" max="200" step="1"></td>
                <td>2968</td>
            </tr>
            <tr>
                <td>Fillet</td>
                <td><input type="number" id="quantity_2957" name="clamshell[quantity_fillet]" min="0" max="200" step="1"></td>
                <td>2957</td>
            </tr>
        </table>
        
      
    </div>

    <div class="box">
        <h1>CAN</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>Fizz</td>
                <td><input type="number"  id="quantity_5924" name="can[quantity_fizz]" min="0" max="200" step="1"></td>
                <td>5924</td>
            </tr>
            <tr>
                <td>Soda</td>
                <td><input type="number"  id="quantity_5479" name="can[quantity_soda]" min="0" max="200" step="1"></td>
                <td>5479</td>
            </tr>
            <tr>
                <td>Cola</td>
                <td><input type="number"  id="quantity_5482"name="can[quantity_cola]" min="0" max="200" step="1"></td>
                <td>5482</td>
            </tr>
            <tr>
                <td>Sprite can</td>
                <td><input type="number"  id="quantity_5447" name="can[quantity_sprite]" min="0" max="200" step="1"></td>
                <td>5447</td>
            </tr>
        </table>

       
    </div></div>

    <div class="container">
        
    <div class="box">
        <h1>POWDER</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>BBQ powder</td>
                <td><input type="number" id="quantity_7890" name="powder[quantity_BBQ]" min="0" max="200" step="1"></td>
                <td>7890</td>
            </tr>
            <tr>
                <td>Cheese</td>
                <td><input type="number" id="quantity_7653" name="powder[quantity_chess]" min="0" max="200" step="1"></td>
                <td>7653</td>
            </tr>
            <tr>
                <td>Breader</td>
                <td><input type="number" id="quantity_7554" name="powder[quantity_breader]" min="0" max="200" step="1"></td>
                <td>7554</td>
            </tr>
            <tr>
                <td>coating</td>
                <td><input type="number" id="quantity_7542" name="powder[quantity_coating]" min="0" max="200" step="1"></td>
                <td>7542</td>
            </tr>
        </table>

        
    </div>


<div class="box">
        <h1>CUPS</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>Mcflurry</td>
                <td><input type="number" id="quantity_5799" name="cups[quantity_Mcflurry]" min="0" max="200" step="1"></td>
                <td>5799</td>
            </tr>
            <tr>
                <td>Sundae</td>
                <td><input type="number" id="quantity_5435" name="cups[quantity_Sundae]" min="0" max="200" step="1"></td>
                <td>5435</td>
            </tr>
            <tr>
                <td>12oz cups</td>
                <td><input type="number" id="quantity_5684" name="cups[quantity_12oz]" min="0" max="200" step="1"></td>
                <td>5684</td>
            </tr>
            <tr>
                <td>16oz cups</td>
                <td><input type="number" id="quantity_5788" name="cups[quantity_16oz]" min="0" max="200" step="1"></td>
                <td>5788</td>
            </tr>
        </table>

        
    </div>
    </div>


<div class="container">
        
    <div class="box">
        <h1>SAUCES</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>BBQ sauce</td>
                <td><input type="number" id="quantity_8092" name="sauces[quantity_bbqs]" min="0" max="200" step="1"></td>
                <td>8092</td>
            </tr>
            <tr>
                <td>sweet</td>
                <td><input type="number" id="quantity_8322" name="sauces[quantity_sweet]" min="0" max="200" step="1"></td>
                <td>8322</td>
            </tr>
            <tr>
                <td>maple</td>
                <td><input type="number" id="quantity_8463" name="sauces[quantity_maple]" min="0" max="200" step="1"></td>
                <td>8463</td>
            </tr>
            <tr>
                <td>syrups</td>
                <td><input type="number" id="quantity_8657" name="sauces[quantity_syrups]" min="0" max="200" step="1"></td>
                <td>8657</td>
            </tr>
        </table>
    </div>


<div class="box">
        <h1>PAPER BAG</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>A</td>
                <td><input type="number" id="quantity_6623" name="paperbag[quantity_A]" min="0" max="200" step="1"></td>
                <td>6623</td>
            </tr>
            <tr>
                <td>B</td>
                <td><input type="number" id="quantity_6621" name="paperbag[quantity_B]" min="0" max="200" step="1"></td>
                <td>6621</td>
            </tr>
            <tr>
                <td>C</td>
                <td><input type="number" id="quantity_6624" name="paperbag[quantity_C]" min="0" max="200" step="1"></td>
                <td>6624</td>
            </tr>
            <tr>
                <td>D</td>
                <td><input type="number" id="quantity_6698" name="paperbag[quantity_D]" min="0" max="200" step="1"></td>
                <td>6698</td>
            </tr>
        </table>
    </div>
    </div>

    <div class="container">
        
        <div class="box">
            <h1>LIDS</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>12oz lids</td>
                    <td><input type="number" id="quantity_9542" name="lids[quantity_12oz]" min="0" max="200" step="1"></td>
                    <td>9542</td>
                </tr>
                <tr>
                    <td>16oz lids</td>
                    <td><input type="number" id="quantity_9422" name="lids[quantity_16oz]" min="0" max="200" step="1"></td>
                    <td>9422</td>
                </tr>
                <tr>
                    <td>dome</td>
                    <td><input type="number" id="quantity_9533" name="lids[quantity_dome]" min="0" max="200" step="1"></td>
                    <td>9533</td>
                </tr>
                <tr>
                    <td>coffee</td>
                    <td><input type="number" id="quantity_9267" name="lids[quantity_coffee]" min="0" max="200" step="1"></td>
                    <td>9267</td>
                </tr>
            </table>
        </div>
    
    
    <div class="box">
            <h1>UTENSIL</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>spoon</td>
                    <td><input type="number" id="quantity_3992" name="utensil[quantity_spoon]" min="0" max="200" step="1"></td>
                    <td>3992</td>
                </tr>
                <tr>
                    <td>knife</td>
                    <td><input type="number" id="quantity_3998" name="utensil[quantity_knife]" min="0" max="200" step="1"></td>
                    <td>3998</td>
                </tr>
                <tr>
                    <td>teaspoon</td>
                    <td><input type="number" id="quantity_2712" name="utensil[quantity_teaspoon]" min="0" max="200" step="1"></td>
                    <td>2712</td>
                </tr>
                <tr>
                    <td>fork</td>
                    <td><input type="number" id="quantity_3532" name="utensil[quantity_fork]" min="0" max="200" step="1"></td>
                    <td>3532</td>
                </tr>
            </table>
        </div>
        </div>

        <div class="container">
        
        <div class="box">
            <h1>BOXES</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>Happy meal Box</td>
                    <td><input type="number" id="quantity_6434" name="boxes[quantity_happymeal]" min="0" max="200" step="1"></td>
                    <td>6434</td>
                </tr>
                <tr>
                    <td>Mcshare box</td>
                    <td><input type="number" id="quantity_6534" name="boxes[quantity_mcsharebox]" min="0" max="200" step="1"></td>
                    <td>6534</td>
                </tr>      
            </table>
        </div>
    
    <div class="box">
            <h1>GRANULES</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>coffee granules</td>
                    <td><input type="number" id="quantity_3993" name="granules[quantity_coffeg]" min="0" max="200" step="1"></td>
                    <td>3993</td>
                </tr>
                <tr>
                    <td>brewed coffee granules</td>
                    <td><input type="number" id="quantity_3999" name="granules[quantity_brewedCoffe]" min="0" max="200" step="1"></td>
                    <td>3999</td>
                </tr>
              
            </table>
        </div>
        </div>

        <div class="container">
        
        <div class="box">
            <h1>TISSUES</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>kitchen tissue</td>
                    <td><input type="number" id="quantity_7321" name="tissues[quantity_kitchenTissues]" min="0" max="200" step="1"></td>
                    <td>7321</td>
                </tr>
                <tr>
                    <td>restroom tissue</td>
                    <td><input type="number" id="quantity_7753" name="tissues[quantity_restroomTissues]" min="0" max="200" step="1"></td>
                    <td>7753</td>
                </tr>
                <tr>
                    <td>serving tissue</td>
                    <td><input type="number" id="quantity_7231" name="tissues[quantity_servingTissues]" min="0" max="200" step="1"></td>
                    <td>7231</td>
                </tr>
                
            </table>
        </div>
        
    <div class="box">
            <h1>DRINKS</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>ice coffee</td>
                    <td><input type="number" id="quantity_6260" name="drinks[quantity_iceCoffe]" min="0" max="200" step="1"></td>
                    <td>6260</td>
                </tr>
                <tr>
                    <td>Sprite</td>
                    <td><input type="number" id="quantity_6259" name="drinks[quantity_sprite]" min="0" max="200" step="1"></td>
                    <td>6259</td>
                </tr>
                <tr>
                    <td>CocaCola</td>
                    <td><input type="number" id="quantity_6257" name="drinks[quantity_CocaCola]" min="0" max="200" step="1"></td>
                    <td>6257</td>
                </tr>
              
            </table>
        </div>
        </div> 
<footer>
    <button type="submit" name="action" value="add">Restock product</button>
    <button type="submit" name="action" value="remove">Deduct Product</button>
</footer>
</form>




     <!-- QR Code Scanner Section -->
     <div id="reader" style="width: 300px;"></div>
    <div id="reader-results"></div>

    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.addEventListener('keydown', function(event) {
            // Assuming the scanner inputs all the text in one go and ends with an Enter key
            if (event.key === 'Enter') {
                const inputField = document.getElementById('scannerInput'); // Input field where scanner inputs the QR code
                const scannedCode = inputField.value.trim(); // Get the scanned code
                inputField.value = ''; // Clear the input field for the next scan
                
                console.log(`Scanned code: ${scannedCode}`);

                const targetField = document.getElementById('quantity_' + scannedCode);
                if (targetField) {
                    console.log(`Input field found for code: ${scannedCode}`);
                    targetField.value = (parseInt(targetField.value) || 0) + 1; // Increment the current value
                } else {
                    console.log(`No matching input field found for code: ${scannedCode}`);
                }
            }
        });
    });
</script>

</body>
</html>



