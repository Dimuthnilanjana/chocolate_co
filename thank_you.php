<!-- thankyou.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('images/85.jpg'); /* Replace 'background-image.jpg' with the path to your image */
    background-size: cover;
    background-position: center;
    height: 100vh;
}
        
        .container {
    position: fixed;
    
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

        h1 {
            color: #333;
        }
        
        p {
            color: #666;
        }
        
        button {
            background-color: #8B4513;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        button:hover {
            background-color:  #A0522D;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thanks for Your Order!</h1>
        <p>Welcome to Chocolate Co.! We offer a wide range of delicious chocolates crafted with love. Enjoy your sweet treats!</p>
        <form action="thank_you.php" method="post">
            <button type="submit" name="logout">Log Out</button>
            <button type="submit" name="shop_more">Shop More</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();

if (isset($_POST['logout'])) {
    // Redirect to logout page
    header("Location: index.php");
    exit;
}

if (isset($_POST['shop_more'])) {
    // Redirect to view products page
    header("Location: view_products.php");
    exit;
}
?>
