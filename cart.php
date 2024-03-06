<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit;
}

// Include MongoDB connection
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Retrieve cart items from MongoDB collection
$query = new MongoDB\Driver\Query(['user_id' => $_SESSION['user_id']]);
$cursor = $manager->executeQuery('cartdatabasetest.cart', $query);
$cartItems = $cursor->toArray();

// Remove item from cart if delete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $item_id = $_POST['delete_item'];
    $bulkWrite = new MongoDB\Driver\BulkWrite();
    $bulkWrite->delete(['_id' => new MongoDB\BSON\ObjectId($item_id)]);
    $manager->executeBulkWrite('cartdatabasetest.cart', $bulkWrite);
    header("Location: cart.php"); // Refresh the page to reflect the changes
    exit;
}

// Initialize TotalAmount
$TotalAmount = 0;

// Calculate total amount
foreach ($cartItems as $item) {
    $TotalAmount += $item->price * $item->quantity;
}

// If "Proceed to Checkout" is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proceed_to_checkout'])) {
    // Include MySQL connection
    include 'components/connect.php';

    // Insert into orders table
    $order_date = date('Y-m-d');
    $user_id = $_SESSION['user_id'];

    $insert_order = $conn->prepare("INSERT INTO orders (order_date, user_id, TotalAmount) VALUES (?, ?, ?)");
    $insert_order->execute([$order_date, $user_id, $TotalAmount]); // Insert total amount along with other data

    // Fetch the last inserted order ID
    $order_id_stmt = $conn->prepare("SELECT LAST_INSERT_ID()");
    $order_id_stmt->execute();
    $order_id_row = $order_id_stmt->fetch(PDO::FETCH_ASSOC);
    $order_id = $order_id_row['LAST_INSERT_ID()'];

    // Insert into order_items table
    foreach ($cartItems as $item) {
        $insert_order_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert_order_item->execute([$order_id, $item->product_id, $item->quantity]);
    }

    // Clear the cart in MongoDB
    $bulkWrite = new MongoDB\Driver\BulkWrite();
    $bulkWrite->delete(['user_id' => $_SESSION['user_id']]);
    $manager->executeBulkWrite('cartdatabasetest.cart', $bulkWrite);

    // Redirect to payment processing page with order ID
    header("Location: payment_processing.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>
   
   <!-- Include any additional CSS styles you need -->
   <style>
body {
    font-family: Arial, sans-serif;
    background-image: url('images/img10.jpg'); /* Replace 'background-image.jpg' with the path to your image */
    background-size: cover;
    background-position: center;
    height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.shopping-cart {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.shopping-cart .heading {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #333;
    color: #fff;
}

tbody tr:hover {
    background-color: #f9f9f9;
}

.delete-button {
    color: #fff;
    background-color: #dc3545;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.delete-button:hover {
    background-color: #c82333;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.checkout-button {
    text-align: right;
}

.checkout-button{
    background-color: #8B4513;
         color: #fff;
         padding: 12px 24px;
         text-decoration: none;
         border-radius: 4px;
         display: inline-block;
}

.checkout-button:hover {
    background-color: #CD853F;
}

.disabled {
    pointer-events: none;
    opacity: 0.5;
}
.option-btn {
         background-color: #4caf50;
         color: #fff;
         padding: 8px 12px;
         text-decoration: none;
         border-radius: 4px;
      }
      .option-btn {
    text-align: right;
}

.header{
   background-color: var(--black);
   position: sticky;
   top: 0; left: 0; right: 0;
   z-index: 1000;
}

.header .flex{
   display: flex;
   align-items: center;
   justify-content: space-between;
}

.header .flex .logo{
   font-size: 2.5rem;
   color: var(--main-color);
   font-weight: bolder;
}

.header .flex .navbar a{
   padding: 1rem 1.3rem;
   background-color: var(--white);
   border-radius: .5rem;
   margin-left: 1rem;
   font-size: 2rem;
   color: var(--black);
}

.header .flex .navbar .cart-icon span{
   margin-left: .3rem;
}

.header .flex .navbar a:hover{
   background-color: var(--main-color);
   color: var(--white);
}


      </style>
</head>
<body>




<section class="cart-section">
    <div class="container">
        <h1>Your Cart</h1>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= $item->product_id ?></td>
                        <td><?= $item->name ?></td>
                        <td><?= $item->price ?></td>
                        <td><?= $item->quantity ?></td>
                        <td><?= $item->price * $item->quantity ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="delete_item" value="<?= $item->_id ?>">
                                <button  class="delete-button" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <!-- Display total amount -->
            <tfoot>
                <tr>
                    <td colspan="4" align="right">Total Amount:</td>
                    <td><?= $TotalAmount ?></td>
                    <td></td> <!-- Empty cell for consistency -->
                </tr>
            </tfoot>
        </table>
        <br>

        <!-- Display total amount -->

        <form method="post" action="">
            <button  class="checkout-button" type="submit" name="proceed_to_checkout">Proceed to Checkout</button>
        </form>
        <br>
        <br>
        <a href="view_products.php" class="option-btn" style="margin-top: 0;">Continue Shopping</a>
    </div>
</section>

<!-- Include any additional scripts you need -->

</body>
</html>
