<?php
session_start(); // Start session

// Check if the order ID is provided in the URL
if(isset($_GET['order_id'])) {
    // Retrieve the order ID from the URL
    $order_id = $_GET['order_id'];

    // Retrieve the total amount from the orders table using the order ID
    include 'components/connect.php';
    $select_order = $conn->prepare("SELECT TotalAmount FROM orders WHERE order_id = ?");
    $select_order->execute([$order_id]);
    $order = $select_order->fetch(PDO::FETCH_ASSOC);

    // Check if the order exists
    if($order) {
        // Total amount retrieved successfully
        $total_amount = $order['TotalAmount'];
    } else {
        // Order not found, redirect back to view products page or display an error message
        header("Location: view_products.php");
        exit;
    }
} else {
    // Order ID not provided in the URL, redirect back to view products page or display an error message
    header("Location: view_products.php");
    exit;
}

// Check if the payment form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay'])) {
    // Retrieve form data
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];

    // Insert data into the orderpayment table
    $insert_payment = $conn->prepare("INSERT INTO orderpayment (order_id, payment_date, payment_method, status) VALUES (?, NOW(), ?, 'paid')");
    $insert_payment->execute([$order_id, $payment_method]);

    // Redirect to a thank you page or display a success message
    header("Location: thank_you.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment </title>
   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         background-image: url('images/img11.png'); /* Replace 'background-image.jpg' with the path to your image */
    background-size: cover;
    background-position: center;
      }

      .payment-form {
         width: 400px;
         background-color: #fff;
         border-radius: 8px;
         padding: 20px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

      .payment-form h2 {
         margin-bottom: 20px;
         text-align: center;
      }

      .payment-form input[type="text"],
      .payment-form input[type="tel"],
      .payment-form select {
         width: 100%;
         padding: 10px;
         margin-bottom: 15px;
         border: 1px solid #ccc;
         border-radius: 5px;
         box-sizing: border-box;
      }

      .payment-form select {
         appearance: none;
      }

      .payment-form button {
         width: 100%;
         padding: 10px;
         background-color: #8B4513;
         color: #fff;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s;
      }

      .payment-form button:hover {
         background-color: #D2B48C;
      }
   </style>
</head>
<body>

<div class="payment-form">
   <h2>Payment Form</h2>
   <form action="<?php echo $_SERVER['PHP_SELF'] . '?order_id=' . $order_id; ?>" method="post">
      <label for="address">Address:</label>
      <input type="text" id="address" name="address" required>

      <label for="phone">Telephone Number:</label>
      <input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890" required>

      <label for="total_amount">Total Amount:</label>
      <input type="text" id="total_amount" name="total_amount" value="<?php echo $total_amount; ?>" readonly>

      <label for="payment_method">Payment Type:</label>
      <select id="payment_method" name="payment_method" required>
         <option value="">Select Payment Method</option>
         <option value="cash_on_delivery">Cash on Delivery</option>
         <option value="card_payment">Card Payment</option>
      </select>

      <button type="submit" name="pay">Pay</button>
   </form>
</div>

</body>
</html>
