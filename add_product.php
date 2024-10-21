<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get next batch number (example function)
function getNextBatch($conn, $product_name) {
    // Implement logic to determine the next batch number
    // This is just an example; modify as per your requirements
    return 1; // Placeholder return value
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_new_product') {
    $category = $_POST['category'];
    $product_name = $_POST['product_name'];
    $code = $_POST['code'];
    $quantity = intval($_POST['quantity']);

    // Sanitize input to prevent SQL injection
    $category = $conn->real_escape_string($category);
    $product_name = $conn->real_escape_string($product_name);
    $code = $conn->real_escape_string($code);
    $quantity = $conn->real_escape_string($quantity);

    // Prepare to insert the new product
    $stmt = $conn->prepare("INSERT INTO `$category` (product_name, quantity, code) VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
    
    if ($stmt) {
        $stmt->bind_param("ssi", $product_name, $quantity, $code);
        if ($stmt->execute()) {
            echo "Product added successfully.";
        } else {
            echo "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Preparation failed: " . $conn->error;
    }

    // Insert into received_history (optional)
    date_default_timezone_set('Asia/Manila');
    $date_time = date('Y-m-d H:i:s');
    $batch = getNextBatch($conn, $product_name); // Make sure this function exists and works

    $stmt = $conn->prepare("INSERT INTO received_history (product_name, code, quantity, date_time, action, batch) VALUES (?, ?, ?, ?, 'added', ?)");
    if ($stmt) {
        $stmt->bind_param("ssisi", $product_name, $code, $quantity, $date_time, $batch);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Failed to insert into received_history: " . $conn->error;
    }
}

// Fetch categories dynamically (modify as needed)
$categories = ["clamshell", "can", "other_category"]; // Replace with actual category names
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form method="post" action="">
        <label for="category">Select Category:</label>
        <select name="category" id="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat; ?>"><?php echo ucfirst($cat); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required><br><br>

        <label for="code">Product Code:</label>
        <input type="text" name="code" id="code" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required><br><br>

        <input type="hidden" name="action" value="add_new_product">
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
