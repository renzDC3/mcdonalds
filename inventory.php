<?php
session_start(); 
if (!isset($_SESSION["user"])) {
   header("Location:index.php");
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

    <table class="table table-striped" id="history">
    <tr>
        <td>Product Name</td>
        <td>Received</td>
        <td>Out</td>
    </tr>
    <?php 
    $categories = ['clamshell', 'can', 'powder', 'cups','sauces','paper-bag','lids','utensil','boxes','granules','tissues','liquid-drinks'];

    // Loop through each category
    foreach ($categories as $category) {
        
        foreach ($_SESSION['added_quantities'][$category] ?? [] as $key => $added) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($key) . '</td>';
            echo '<td>' . htmlspecialchars($added) . '</td>';
            echo '<td>' . htmlspecialchars($_SESSION['removed_quantities'][$category][$key] ?? 0) . '</td>';
            echo '</tr>';
        }
    }
    ?>
</table>


<form action="save_inventory.php" method="POST">

          
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
        
        <h2>Stock</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Code</th>
            </tr>
          
            <?php if (isset($latest_data['clamshell'])): ?>
                <?php foreach ($latest_data['clamshell'] as $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($data['code']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?> </table>
       
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
                <td><input type="number" name="can[quantity_mcfizz]" min="0" max="200" step="1"></td>
                <td>5924</td>
            </tr>
            <tr>
                <td>Soda</td>
                <td><input type="number" name="can[quantity_soda]" min="0" max="200" step="1"></td>
                <td>5479</td>
            </tr>
            <tr>
                <td>Cola</td>
                <td><input type="number" name="can[quantity_mccola]" min="0" max="200" step="1"></td>
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
        <h1>Sauces</h1>
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


<footer>
    <button type="submit" name="action" value="add">Received</button>
    <button type="submit" name="action" value="remove">Out</button>
</footer>
</form>
</body>
</html>
