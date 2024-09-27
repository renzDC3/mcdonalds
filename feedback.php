<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="#.css">

    
    <script src="loadLayout.js" defer>
       
    </script>
    

    <title></title>
</head>
<style>
h1{text-align:center;

}
h2 {
  background: #666;
  font-family: sans-serif;
  width: 100vw;
  height: 100vh;
  margin: 0;
}

form {
  background: #fff;
  position: relative;
  display: inline-block;
  padding: 20px;
  left: 50%;
  top: 50%;
  transform: translate(-50%,-50%);
  width: 500px;
  border-radius: 5px;
}


form textarea {
  width: calc(100% - 22px);
  height: 100px;
  resize: none;
  border: 1px solid #999;
  border-radius: 4px;
  padding: 10px;
}

form button {
  background: #999;
  padding: 10px 20px;
  color: #fff;
  border: 1px solid #999;
  border-radius: 4px;
  transition: 0.1s;
}

form button:hover {
  background: #fff;
  color: #666;
}
</style>

<body>
    <h1>feedback</h1>
    <h2>
        <form>
  <br/>
  <p>Please leave a comment below</p>
  <textarea name="comment">"Comment Here"</textarea>
  <br/>
  <br/>
  <button>Send Feedback</button>
</form>
</h2>
</body>

</html>




