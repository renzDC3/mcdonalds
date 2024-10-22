<?php
// Set the timezone
date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mcdonalds_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve all received products and their details
$query = "
    SELECT 
        rh.product_name, 
        rh.code, 
        rh.quantity, 
        rh.date_time AS received_date, 
        rh.expiration_date,  -- Directly select expiration_date from the table
        ROW_NUMBER() OVER (PARTITION BY rh.product_name, rh.code ORDER BY rh.date_time) AS batch_number
    FROM 
        received_history rh 
    WHERE 
        rh.action = 'added' 
        AND rh.product_name IN ('BBQ powder', 'Cheese', 'Breader', 'coating', 'BBQs', 'sweet', 'maple', 'syrups', 'coffee granules', 'brewed coffee granules', 'ice coffee', 'Sprite', 'CocaCola')
    ORDER BY 
        rh.product_name, rh.code";

$result = $conn->query($query);

$expiredProducts = [];
$currentDate = new DateTime();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Use the expiration_date from the database directly
        $expirationDate = new DateTime($row['expiration_date']);
        
        // Check if the product has expired
        if ($expirationDate < $currentDate) {
            $expiredProducts[] = [
                'product_name' => $row['product_name'],
                'batch_number' => $row['batch_number'],
                'code' => $row['code'],
                'quantity' => $row['quantity']  // Include quantity in the expired products array
            ];
        }
    }
}

// List of all product names (unchanged)
$allProducts = [
    ['1PC', 2967, 'Clamshell'], ['2PC', 2987, 'Clamshell'], ['Fillet', 2957, 'Clamshell'],
    ['Spaghetti', 2968, 'Clamshell'], ['Cola', 5482, 'Can'], ['Fizz', 5924, 'Can'],
    ['Soda', 5479, 'Can'], ['Sprite can', 5447, 'Can'], ['BBQ powder', 7890, 'Powder'],
    ['Breader', 7554, 'Powder'], ['Cheese', 7653, 'Powder'], ['coating', 7542, 'Powder'],
    ['12oz cups', 5684, 'Cups'], ['16oz cups', 5788, 'Cups'], ['Mcflurry', 5799, 'Cups'],
    ['Sundae', 5435, 'Cups'], ['BBQ sauce', 8092, 'Sauces'], ['maple', 8463, 'Sauces'],
    ['sweet', 8322, 'Sauces'], ['syrups', 8657, 'Sauces'], ['A', 8092, 'Paperbag'],
    ['B', 8322, 'Paperbag'], ['C', 8463, 'Paperbag'], ['D', 8657, 'Paperbag'],
    ['12oz lids', 9542, 'Lids'], ['16oz lids', 9422, 'Lids'], ['coffee', 9267, 'Lids'],
    ['dome', 9533, 'Lids'], ['fork', 3532, 'Utensil'], ['knife', 3998, 'Utensil'],
    ['spoon', 3992, 'Utensil'], ['teaspoon', 2712, 'Utensil'], ['Happy meal Box', 6434, 'Boxes'],
    ['Mcshare box', 6534, 'Boxes'], ['brewed coffee granules', 3999, 'Granules'],
    ['coffee granules', 3993, 'Granules'], ['kitchen tissue', 7321, 'Tissues'],
    ['restroom tissue', 7753, 'Tissues'], ['serving tissue', 7231, 'Tissues'],
    ['CocaCola', 6257, 'Drinks'], ['ice coffee', 6260, 'Drinks'], ['Sprite', 6259, 'Drinks']
];

$foundProducts = [];

// Check each table for product quantities
foreach (['clamshell', 'can', 'powder', 'cups', 'sauces', 'paperbag', 'lids', 'utensil', 'boxes', 'granules', 'tissues', 'drinks'] as $table) {
    $result = $conn->query("SELECT product_name FROM $table");
    while ($row = $result->fetch_assoc()) {
        $foundProducts[] = $row['product_name'];
    }
}

$outOfStockProducts = [];
foreach ($allProducts as $product) {
    if (!in_array($product[0], $foundProducts)) {
        $outOfStockProducts[] = $product[0] . " (Code: " . $product[1] . ")";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layoutnav.css">
    <script src="loadLayout.js" defer></script>
    <title>Inventory System</title>
</head>
<style> #accounticon{
height:40px;
width: 50px;
}
.messageExpired{
    font-size: 14px;
    width: 300px;
    margin-left: 1030px;
    margin-top: 100px;
    position: absolute;
   
}
@import
url('https://fonts.googleapis.com/css?family=Slabo+27px');
.h1 {

margin-top:20px;
    letter-spacing: -.15ch;
    line-height: .75;
position:absolute;
margin-left:200px;
width: 100%;

font-size: 25px;
z-index: 1;
font-size: 30px;
    -webkit-text-stroke-width: 0.1px;
  -webkit-text-stroke-color: black;
    font-weight: 600;
    color:   #ffc72ccc !important; 
    text-shadow: 2px 2px 2px rgb(0, 0, 0); /* Adjust the values for desired effect */
    line-height: 20px;
 
    cursor: pointer;
  
}

.h1:after {
    margin-right:200px;
    position:absolute;
  content: "";
  width: 100%;
  height: 100%;
  line-height: 48px;
  z-index: 1;

  animation: abomination1 2.5s linear 1;
  animation-fill-mode: forwards;

    
}


  @keyframes abomination1 {
  0% {
    content: "W";
  }
  4% {
    content: "We";
  }
  8% {
    content: "Wel";
  }
  12% {
    content: "Welc";
  }
  16% {
    content: "Welco";
  }
  20% {
    content: "Welcom";
  }
  24% {
    content: "Welcome";
  }
  28% {
    content: "Welcome ";
  }
  32% {
    content: "Welcome t";
  }
  40% {
    content: "Welcome to";
  }
    content: "The Typewrite";
  
  46% {
    content: "Welcome to I";
  }
  51% {
    content: "Welcome to In";
  }
  57% {
    content: "Welcome to Inv";
  }
  60% {
    content: "Welcome to Inve";
  }
  64% {
    content: "Welcome to Inven ";
  }
  70% {
    content: "Welcome to Invent";
  }
  72% {
    content: "Welcome to Invento";
  }
  79% {
    content: "Welcome to Inventor";
  }
  82% {e
    content: "Welcome to Inventory";
  }
  83% {
    content: "Welcome to Inventory S";
  }
  86% {
    content: "Welcome to Inventory Sy";
  }
  90% {
    content: "Welcome to Inventory Sys";
  }
  95% {
    content: "Welcome to Inventory Syste";
  }
  100% {
    content: "Welcome to Inventory System";
  }
  }
</style>
<body>


<nav>
    <input type="checkbox" id="check">
    <label for="check" class="check-btn">
        <i class="fas fa-bars"></i>
    </label>

    <img style="margin-top:20px;margin-left:60px;"src="topicon.png" alt="McDo Icon" class="top-icon">
    

    <ul>
    
    <li class="log_out"><a id="account" onclick="toggleIframe()"> <img src="accountIcon.png" id="accounticon"></a></li>
   
    </ul>

    
</nav>
<h4 class="h1"></h4>
<div class="messageExpired">
        <?php if (!empty($expiredProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are expired:
                <ul>
                <?php foreach ($expiredProducts as $product): ?>
                    <li>
                        <?php echo htmlspecialchars($product['product_name']) . " (Batch: " . $product['batch_number'] . ", Received: " . $product['quantity'] . ")"; ?>
                        <form action="remove_expired.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>">
                            <input type="hidden" name="code" value="<?php echo htmlspecialchars($product['code']); ?>">
                           
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($outOfStockProducts)): ?>
            <div class="alert alert-danger" role="alert">
                The following products are out of stock:
                <ul>
                <?php foreach ($outOfStockProducts as $product): ?>
                    <li><?php echo htmlspecialchars($product); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
  
    <footer>
  
    <p>&copy; MCFOOD / Quezon City / 09912328099 /<br>
       &copy; MCDONALDS RIZAL/ MALABON CITY /6065-84664</p>
  
</footer>

     
</body>
</html>