<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: /mcdonalds/inventory.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <style>
    

</style>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="inventory-design.css">

    <script src="/mcdonalds/loadLayout.js" defer></script>
    
    <title>Boxes</title>

</head>
<h1>Boxes</h1>
<body>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">ID#</th>
      <th scope="col">Productname>
      <th scope="col">Quantity</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <tr>
      <th scope="row">1</th>
      <td>Small</td>
      <td>123</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Meduim</td>
      <td>342</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Large</td>
      <td>765</td>
    </tr>
  </tbody>
</table>
        

</body>
</html>