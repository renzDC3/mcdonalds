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

    
<form action="save_inventory.php" method="POST">
<div class="history"></div>


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
                <td>Seasoning</td>
                <td><input type="number" name="powder[quantity_seasoning]" min="0" max="200" step="1"></td>
                <td>7890</td>
            </tr>
            <tr>
                <td>SpiceMix</td>
                <td><input type="number" name="powder[quantity_spicemix]" min="0" max="200" step="1"></td>
                <td>7653</td>
            </tr>
            <tr>
                <td>FryCoating</td>
                <td><input type="number" name="powder[quantity_frycoating]" min="0" max="200" step="1"></td>
                <td>7554</td>
            </tr>
            <tr>
                <td>ShakeMix</td>
                <td><input type="number" name="powder[quantity_shakemix]" min="0" max="200" step="1"></td>
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
</div>

<footer>
    <button type="submit" name="action" value="add">Received</button>
    <button type="submit" name="action" value="remove">Out</button>
</footer>
</form>
</body>
</html>
