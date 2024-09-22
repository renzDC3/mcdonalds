<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: /mcdonalds/inventory.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">

</head>

<body>
<table>
  <tr>
    <th>Product Name</th>
    <th>Boxes Get</th>
    <th>Code</th>
  </tr>
  <tr>

    <td>name</td>
     <td>
        <input type="number" name="quantity" min="1" max="200" step="1"></input>
    </td>
    <td>7890</td>
    </tr>
  <td>name</td>
     <td>
        <input type="number" name="quantity" min="1" max="200" step="1"></input>
   
    </td>
    <td>7653</td>
  </tr>

  <td>name</td>
     <td>
        <input type="number" name="quantity" min="1" max="200" step="1"></input>
    </td>
    <td>7554</td>
  </tr>

  <td>name</td>
     <td>
        <input type="number" name="quantity" min="1" max="200" step="1"></input>
       </div>
    </td>
    <td>7542</td>
  </tr>
</table>

    
    
</body>
</html>