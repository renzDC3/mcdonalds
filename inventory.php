<?php
session_start(); 
if (!isset($_SESSION["user"])) {
   header("Location: index.php");
   exit();
}

$latest_data = isset($_SESSION['latest_data']) ? $_SESSION['latest_data'] : [];
$alerts = isset($_SESSION['alerts']) ? $_SESSION['alerts'] : [];
unset($_SESSION['alerts']); // Clear alerts after displaying


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="inventory.css">
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

    <table class="table table-striped"id="history">
    <tr class="tr1">
        <td class="td1">Product Name</td>
        <td class="td1">Received</td>
        <td class="td1">Get</td>
    </tr>
    <?php 
    $categories = ['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'];

    foreach ($categories as $category) {
        // Combine both added and removed quantities into a single array of product names
        $product_names = array_unique(
            array_merge(
                array_keys($_SESSION['added_quantities'][$category] ?? []),
                array_keys($_SESSION['removed_quantities'][$category] ?? [])
            )
        );

        foreach ($product_names as $product_name) {
            $added = $_SESSION['added_quantities'][$category][$product_name] ?? 0;
            $removed = $_SESSION['removed_quantities'][$category][$product_name] ?? 0;
            
            echo '<tr>';
            echo '<td>' . htmlspecialchars($product_name) . '</td>';
            echo '<td>' . htmlspecialchars($added) . '</td>';
            echo '<td>' . htmlspecialchars($removed) . '</td>';
            echo '</tr>';
        }
    }
    ?>
    </table>

<form action="save_inventory.php" method="POST">
    <div><intput></input></div>
<div class="container">
    <div class="box">
        <h1>CLAMSHELL</h1>

        <table>
            
            <tr>
                <th>Product Name</th>
                <th>Boxes Get</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>1PC</td>
                <td><input type="number" name="clamshell[quantity_1pc]" min="0" max="200" step="1"></td>
                <td>2967</td>
            </tr>
            <tr>
                <td>2PC</td>
                <td><input type="number" name="clamshell[quantity_2pc]" min="0" max="200" step="1"></td>
                <td>2987</td>
            </tr>
            <tr>
                <td>Spaghetti</td>
                <td><input type="number" name="clamshell[quantity_spaghetti]" min="0" max="200" step="1"></td>
                <td>2968</td>
            </tr>
            <tr>
                <td>Fillet</td>
                <td><input type="number" name="clamshell[quantity_fillet]" min="0" max="200" step="1"></td>
                <td>2957</td>
            </tr>
        </table>
        
      
    </div>

    <div class="box">
        <h1>CAN</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes Get</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>Fizz</td>
                <td><input type="number" name="can[quantity_fizz]" min="0" max="200" step="1"></td>
                <td>5924</td>
            </tr>
            <tr>
                <td>Soda</td>
                <td><input type="number" name="can[quantity_soda]" min="0" max="200" step="1"></td>
                <td>5479</td>
            </tr>
            <tr>
                <td>Cola</td>
                <td><input type="number" name="can[quantity_cola]" min="0" max="200" step="1"></td>
                <td>5482</td>
            </tr>
            <tr>
                <td>Sprite</td>
                <td><input type="number" name="can[quantity_sprite]" min="0" max="200" step="1"></td>
                <td>5447</td>
            </tr>
        </table>

        <h2>Stock</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
            <?php if (isset($latest_data['can'])): ?>
                <?php foreach ($latest_data['can'] as $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($data['code']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div></div>

    <div class="container">
        
    <div class="box">
        <h1>POWDER</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes Get</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>BBQ</td>
                <td><input type="number" name="powder[quantity_BBQ]" min="0" max="200" step="1"></td>
                <td>7890</td>
            </tr>
            <tr>
                <td>Cheese</td>
                <td><input type="number" name="powder[quantity_chess]" min="0" max="200" step="1"></td>
                <td>7653</td>
            </tr>
            <tr>
                <td>Breader</td>
                <td><input type="number" name="powder[quantity_breader]" min="0" max="200" step="1"></td>
                <td>7554</td>
            </tr>
            <tr>
                <td>coating</td>
                <td><input type="number" name="powder[quantity_coating]" min="0" max="200" step="1"></td>
                <td>7542</td>
            </tr>
        </table>

        <h2>Stock</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
            <?php if (isset($latest_data['powder'])): ?>
                <?php foreach ($latest_data['powder'] as $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($data['code']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>


<div class="box">
        <h1>CUPS</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes Get</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>Mcflurry</td>
                <td><input type="number" name="cups[quantity_Mcflurry]" min="0" max="200" step="1"></td>
                <td>5799</td>
            </tr>
            <tr>
                <td>Sundae</td>
                <td><input type="number" name="cups[quantity_Sundae]" min="0" max="200" step="1"></td>
                <td>5435</td>
            </tr>
            <tr>
                <td>12oz</td>
                <td><input type="number" name="cups[quantity_12oz]" min="0" max="200" step="1"></td>
                <td>5684</td>
            </tr>
            <tr>
                <td>16oz</td>
                <td><input type="number" name="cups[quantity_16oz]" min="0" max="200" step="1"></td>
                <td>5788</td>
            </tr>
        </table>

        <h2>Stock</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
            <?php if (isset($latest_data['cups'])): ?>
                <?php foreach ($latest_data['cups'] as $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($data['code']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
    </div>


<div class="container">
        
    <div class="box">
        <h1>SAUCES</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes Get</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>BBQs</td>
                <td><input type="number" name="sauces[quantity_bbqs]" min="0" max="200" step="1"></td>
                <td>8092</td>
            </tr>
            <tr>
                <td>sweet</td>
                <td><input type="number" name="sauces[quantity_sweet]" min="0" max="200" step="1"></td>
                <td>8322</td>
            </tr>
            <tr>
                <td>maple</td>
                <td><input type="number" name="sauces[quantity_maple]" min="0" max="200" step="1"></td>
                <td>8463</td>
            </tr>
            <tr>
                <td>syrups</td>
                <td><input type="number" name="sauces[quantity_syrups]" min="0" max="200" step="1"></td>
                <td>8657</td>
            </tr>
        </table>

        <h2>Stock</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
            <?php if (isset($latest_data['sauces'])): ?>
                <?php foreach ($latest_data['sauces'] as $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($data['code']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>


<div class="box">
        <h1>PAPER BAG</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Boxes Get</th>
                <th>Code</th>
            </tr>
            <tr>
                <td>A</td>
                <td><input type="number" name="paperbag[quantity_A]" min="0" max="200" step="1"></td>
                <td>6623</td>
            </tr>
            <tr>
                <td>B</td>
                <td><input type="number" name="paperbag[quantity_B]" min="0" max="200" step="1"></td>
                <td>6621</td>
            </tr>
            <tr>
                <td>C</td>
                <td><input type="number" name="paperbag[quantity_C]" min="0" max="200" step="1"></td>
                <td>6624</td>
            </tr>
            <tr>
                <td>D</td>
                <td><input type="number" name="paperbag[quantity_D]" min="0" max="200" step="1"></td>
                <td>6698</td>
            </tr>
        </table>

        <h2>Stock</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
            <?php if (isset($latest_data['paperbag'])): ?>
                <?php foreach ($latest_data['paperbag'] as $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($data['code']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
    </div>

    <div class="container">
        
        <div class="box">
            <h1>LIDS</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes Get</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>12oz</td>
                    <td><input type="number" name="lids[quantity_12oz]" min="0" max="200" step="1"></td>
                    <td>9542</td>
                </tr>
                <tr>
                    <td>16oz</td>
                    <td><input type="number" name="lids[quantity_6oz]" min="0" max="200" step="1"></td>
                    <td>9422</td>
                </tr>
                <tr>
                    <td>dome</td>
                    <td><input type="number" name="lids[quantity_dome]" min="0" max="200" step="1"></td>
                    <td>9533</td>
                </tr>
                <tr>
                    <td>coffee</td>
                    <td><input type="number" name="lids[quantity_coffee]" min="0" max="200" step="1"></td>
                    <td>9267</td>
                </tr>
            </table>
    
            <h2>Stock</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Code</th>
                </tr>
                <?php if (isset($latest_data['lids'])): ?>
                    <?php foreach ($latest_data['lids'] as $data) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($data['code']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    
    
    <div class="box">
            <h1>UTENSIL</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes Get</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>spoon</td>
                    <td><input type="number" name="utensil[quantity_spoon]" min="0" max="200" step="1"></td>
                    <td>3992</td>
                </tr>
                <tr>
                    <td>knife</td>
                    <td><input type="number" name="utensil[quantity_knife]" min="0" max="200" step="1"></td>
                    <td>3998</td>
                </tr>
                <tr>
                    <td>teaspoon</td>
                    <td><input type="number" name="utensil[quantity_teaspoon]" min="0" max="200" step="1"></td>
                    <td>2712</td>
                </tr>
                <tr>
                    <td>fork</td>
                    <td><input type="number" name="utensil[quantity_fork]" min="0" max="200" step="1"></td>
                    <td>3532</td>
                </tr>
            </table>
    
            <h2>Stock</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Code</th>
                </tr>
                <?php if (isset($latest_data['utensil'])): ?>
                    <?php foreach ($latest_data['utensil'] as $data) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($data['code']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        </div>

        <div class="container">
        
        <div class="box">
            <h1>BOXES</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes Get</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>Happy meal Box</td>
                    <td><input type="number" name="boxes[quantity_happymeal]" min="0" max="200" step="1"></td>
                    <td>6434</td>
                </tr>
                <tr>
                    <td>Mcshare box</td>
                    <td><input type="number" name="boxes[quantity_mcsharebox]" min="0" max="200" step="1"></td>
                    <td>6534</td>
                </tr>
                
            </table>
    
            <h2>Stock</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Code</th>
                </tr>
                <?php if (isset($latest_data['boxes'])): ?>
                    <?php foreach ($latest_data['boxes'] as $data) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($data['code']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        
    
    
    <div class="box">
            <h1>GRANULES</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes Get</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>coffee granules</td>
                    <td><input type="number" name="granules[quantity_iceCoffe]" min="0" max="200" step="1"></td>
                    <td>3993</td>
                </tr>
                <tr>
                    <td>brewed coffee granules</td>
                    <td><input type="number" name="granules[quantity_brewedCoffe]" min="0" max="200" step="1"></td>
                    <td>3999</td>
                </tr>
              
            </table>
    
            <h2>Stock</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Code</th>
                </tr>
                <?php if (isset($latest_data['granules'])): ?>
                    <?php foreach ($latest_data['granules'] as $data) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($data['code']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        </div>

        <div class="container">
        
        <div class="box">
            <h1>TISSUES</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes Get</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>kitchen tissue</td>
                    <td><input type="number" name="tissue[quantity_kitchenTissues]" min="0" max="200" step="1"></td>
                    <td>7321</td>
                </tr>
                <tr>
                    <td>restroom tissue</td>
                    <td><input type="number" name="tissues[quantity_restroomTissues]" min="0" max="200" step="1"></td>
                    <td>7753</td>
                </tr>
                <tr>
                    <td>serving tissue</td>
                    <td><input type="number" name="tissues[quantity_servingTissues]" min="0" max="200" step="1"></td>
                    <td>7231</td>
                </tr>
                
            </table>
    
            <h2>Stock</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Code</th>
                </tr>
                <?php if (isset($latest_data['tissues'])): ?>
                    <?php foreach ($latest_data['tissues'] as $data) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($data['code']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        
    <div class="box">
            <h1>DRINKS</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Boxes Get</th>
                    <th>Code</th>
                </tr>
                <tr>
                    <td>ice coffee</td>
                    <td><input type="number" name="drinks[quantity_iceCoffe]" min="0" max="200" step="1"></td>
                    <td>6260</td>
                </tr>
                <tr>
                    <td>Sprite</td>
                    <td><input type="number" name="drinks[quantity_sprite]" min="0" max="200" step="1"></td>
                    <td>6259</td>
                </tr>
                <tr>
                    <td>CocaCola</td>
                    <td><input type="number" name="drinks[quantity_CocaCola]" min="0" max="200" step="1"></td>
                    <td>6257</td>
                </tr>
              
            </table>
    
            <h2>Stock</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Code</th>
                </tr>
                <?php if (isset($latest_data['drinks'])): ?>
                    <?php foreach ($latest_data['drinks'] as $data) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($data['code']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        </div>
<footer>
    <button type="submit" name="action" value="add">Received</button>
    <button type="submit" name="action" value="remove">Get</button>
</footer>
</form>
</body>
</html>
