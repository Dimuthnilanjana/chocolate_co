<?php
session_start(); // Start session

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$dbname = "shop_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query database to check if user exists with the provided username and password
    $sql = "SELECT user_id FROM users WHERE user_name = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User exists, set session variable and redirect to view products page
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id']; // Set user_id in session
        $_SESSION['$user_name']=$row['user_name'];
        header("Location: view_products.php"); // Redirect to view products page
        exit;
    } else {
        // Invalid credentials, display error message or handle as needed
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Login</title>
   <!-- CSS styles -->
   <style>
      body {
         font-family: Arial, sans-serif;
         
         background-image: url('images/img10.jpg'); /* Replace 'background-image.jpg' with the path to your image */
    background-size: cover;
    background-position: center;
    height: 100vh;
}
      
      .container {
         max-width: 400px;
         margin: 100px auto;
         background: #fff;
         padding: 20px;
         border-radius: 5px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h2 {
         text-align: center;
      }
      label {
         display: block;
         margin-bottom: 5px;
      }
      input[type="text"],
      input[type="password"] {
         width: 100%;
         padding: 8px;
         margin-bottom: 10px;
         border: 1px solid #ccc;
         border-radius: 3px;
         box-sizing: border-box;
      }
      input[type="submit"] {
         background-color:  #8B4513;
         color: white;
         padding: 10px 20px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         width: 100%;
      }
      input[type="submit"]:hover {
         background-color: #A0522D;
      }
      p {
         text-align: center;
         margin-top: 15px;
      }
      p a {
         color: #007bff;
         text-decoration: none;
      }
      p a:hover {
         text-decoration: underline;
      }

      form{
    
    width: 400px;
    background-color: rgba(255,255,255,0.13);
    position: absolute;
    transform: translate(-50%,-50%);
    top: 50%;
    left: 50%;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 40px rgba(8,7,16,0.6);
    padding: 50px 35px;
}
   </style>
</head>
<body>

<div class="container">
    
    <form action="login.php" method="post">
        <!-- Username and password fields -->
        <h2>Login for searching a flavour</h2>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <!-- Submit button -->
        <input type="submit" value="Login">
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </form>
    <!-- Display error message if login fails -->
    <?php if(isset($error_message)) { ?>
        <p><?php echo $error_message; ?></p>
    <?php } ?>
    
</div>

</body>
</html>
