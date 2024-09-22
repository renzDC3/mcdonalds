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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="table.css">
    

    
    

</head>
<style>

table {
    
    width: 700px;
}
th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
    background-color: #f2f2f2;

}


</style>
<body>
        


<table>

<tr>
  <td>PRODUCT</td>
     <td>BOXES
    </td>
    <td>DATE TIME</td>
    <td>CODE</td>
  </tr>

  <tr>
  <td>MEDIUM CUP</td>
     <td>5
    </td>
    <td>5/1/24 <br>5:30AM</td>
    <td>0463</td>
  </tr>

  <tr>
  <td>SPOON</td>
     <td>6
    </td>
    <td>  5/1/24 <br>5:30AM
    </td>
    <td>0632</td>
  </tr>

  <tr>
  <td>2PC CLAMSHELL</td>
   
    <td>3</td>
    <td>5/1/24 <br>5:30AM</td>
    <td>6732
    </td>
  </tr>

  <tr>
  <td>REGULAR LID</td>
     <td>3
    </td>
    <td>5/1/24 <br>5:30AM</td>
    <td>8721</td>
  </tr>



</table>
    
          

    
</body>
</html>