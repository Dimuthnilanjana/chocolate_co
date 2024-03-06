<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$dbname = "shop_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process sign up form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match";
        exit;
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (user_name, user_add, user_mail, user_tel, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $address, $email, $phone, $password);
    if ($stmt->execute()) {
        // Redirect to login page
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User sign up</title>
   <link rel="stylesheet" href="styles.css">


   <style>

.container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
   
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.container h2 {
    text-align: center;
}
body {
    
    background-image: url('images/img10.jpg'); /* Replace 'background-image.jpg' with the path to your image */
    background-size: cover;
    background-position: center;
    height: 100vh;
}

form{
    height: 520px;
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

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 100%;
    background-color: #8B4513;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #A0522D;
}

p {
    margin-top: 20px;
    text-align: center;
}

p a {
    color: #007bff;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}
.background{
    width: 430px;
    height: 520px;
    position: absolute;
    transform: translate(-50%,-50%);
    left: 50%;
    top: 50%;
}
.background .shape{
    height: 200px;
    width: 200px;
    position: absolute;
    border-radius: 50%;
}
.shape:first-child{
    background: linear-gradient(
        #1845ad,
        #23a2f6
    );
    left: -80px;
    top: -80px;
}
.shape:last-child{
    background: linear-gradient(
        to right,
        #ff512f,
        #f09819
    );
    right: -30px;
    bottom: -80px;
}


   </style>
</head>
<body>

<div class="container">
    
    <form action="signup.php" method="post">
    <h2>Welcome to Chocolate Co. </h2>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input  type="submit" value="Sign Up">
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</div>

</body>
</html>
