<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="layout.css">
    <script src="loadLayout.js" defer></script> 
    
</head>
<body>
    
    <br>
    <div class="container">
        
    <form action="registration.php" method="post">
            <div class="form-group">
            <h1 style="color: white;">register</h1><br>
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:" required>
                <div class="form-btn"><br>
                <input type="submit" class="btn btn-primary" value="Register" name="submit"></div><br>
                <p style="color: white;">Already Registered <a href="login.php">Login Here</a></p>
            </div>
        </form>
        </div>


        <div class="message1">
        <?php
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();
            
            if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
                array_push($errors, "All fields are required");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 12) {
                array_push($errors, "Password must be at least 12 characters long");
            }
            if (!preg_match('/[A-Za-z]/', $password)) {
                array_push($errors, "Password must include at least one letter");
            }
            
            if (!preg_match('/[0-9]/', $password)) {
                array_push($errors, "Password must include at least one number");
            }
            
            if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
                array_push($errors, "Password must include at least one symbol");
            }
            
            if ($password !== $passwordRepeat) {
                array_push($errors, "Passwords do not match");
            }
            require_once "database.php";
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                array_push($errors, "Email already exists!");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                
                $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("sss",$fullName,$email,$passwordHash);
                    $stmt->execute();
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?></div>

        
    
    <br><br><br><br>

    
</body>
</html>
