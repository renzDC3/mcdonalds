<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: /mcdonalds/inventory.php");
   exit();
}

// Fetch the latest data from the session
$latest_data = isset($_SESSION['latest_data']) ? $_SESSION['latest_data'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="table.css">
</head>
<body>
<form action="save_inventory.php" method="POST">
    <input type="hidden" name="table_name" value="products">
    <input type="hidden" name="action" value="add"> <!-- Default action -->
    <table>
        <tr>
            <th>Product Name</th>
            <th>Boxes Get</th>
            <th>Code</th>
        </tr>
        <tr>
            <td>1PC</td>
            <td><input type="number" name="quantity_1pc" min="1" max="200" step="1"></td>
            <td>2967</td>
        </tr>
        <tr>
            <td>2PC</td>
            <td><input type="number" name="quantity_2pc" min="1" max="200" step="1"></td>
            <td>2987</td>
        </tr>
        <tr>
            <td>Spaghetti</td>
            <td><input type="number" name="quantity_spaghetti" min="1" max="200" step="1"></td>
            <td>2968</td>
        </tr>
        <tr>
            <td>Fillet</td>
            <td><input type="number" name="quantity_fillet" min="1" max="200" step="1"></td>
            <td>2957</td>
        </tr>
    </table>
    <button type="submit" name="action" value="add">Received</button>
    <button type="submit" name="action" value="remove">Out </button>
</form>

    <!-- Display the latest data -->
     
    <h2>Stock</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Total Quantity</th>
            <th>Code</th>
        </tr>
        <?php foreach ($latest_data as $data) : ?>
        <tr>
            <td><?php echo htmlspecialchars($data['product_name']); ?></td>
            <td><?php echo htmlspecialchars($data['total_quantity']); ?></td>
            <td><?php echo htmlspecialchars($data['code']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
