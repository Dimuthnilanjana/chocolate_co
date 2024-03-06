<?php
session_start(); // Start session
// MongoDB connection
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('review.customers', $query);
// Convert cursor to Array
$documents = $cursor->toArray();
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    // Retrieve form data
    $review = $_POST['review'];
    $product_name = $_POST['product_name'];

    // Create new document object
    $document = new stdClass();
    $document->review = $review;
    $document->product_name = $product_name;

    // Insert document into MongoDB collection
    $bulkWrite = new MongoDB\Driver\BulkWrite();
    $bulkWrite->insert($document);
    $manager->executeBulkWrite('review.customers', $bulkWrite);

    // Redirect to the same page to refresh the records
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
// Check if form is submitted (for adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
   // Get product ID and quantity from the form
   $product_id = $_POST['product_id'];
   $quantity = $_POST['qty'];

   // Fetch product details from MySQL
   include 'components/connect.php';
   $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
   $select_product->execute([$product_id]);
   $product = $select_product->fetch(PDO::FETCH_ASSOC);

   // Store product details in session cart array
   if (!isset($_SESSION['cart'])) {
       $_SESSION['cart'] = array();
   }
   $_SESSION['cart'][] = array(
       'product_id' => $product_id,
       'name' => $product['name'],
       'price' => $product['price'],
       'quantity' => $quantity,
       'user_id' => $_SESSION['user_id'] // Include user ID in cart details
   );

   // Redirect to prevent form resubmission
   header("Location: {$_SERVER['PHP_SELF']}");
   exit;
}

// Check if cart is not empty
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
   // Insert cart details into MongoDB collection
   $bulkWrite = new MongoDB\Driver\BulkWrite();
   foreach ($_SESSION['cart'] as $item) {
       $document = [
           'product_id' => $item['product_id'],
           'name' => $item['name'],
           'price' => $item['price'],
           'quantity' => $item['quantity'],
           'user_id' => $item['user_id'] // Include user ID in MongoDB document
       ];
       $bulkWrite->insert($document);
   }
   $manager->executeBulkWrite('cartdatabasetest.cart', $bulkWrite);

   // Clear the session cart after storing in MongoDB
   unset($_SESSION['cart']);
}


include 'components/connect.php';


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_now'])) {
   // Get product ID and quantity from the form
   $product_id = $_POST['product_id'];
   $quantity = $_POST['qty'];
  
   // Fetch product details
   $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
   $select_product->execute([$product_id]);
   $product = $select_product->fetch(PDO::FETCH_ASSOC);
  
   // Calculate total amount
   $total_amount = $product['price'] * $quantity;
  
   // Get current date
   $order_date = date('Y-m-d');

   // Get user ID of the currently logged-in user
   $user_id = $_SESSION['user_id'];

   // Insert into orders table
   $insert_order = $conn->prepare("INSERT INTO orders (order_date, user_id, TotalAmount) VALUES (?, ?, ?)");
   $insert_order->execute([$order_date, $user_id, $total_amount]);
   $order_id = $conn->lastInsertId(); // Retrieve the auto-incremented order ID
   
   // Insert into order_items table
   $insert_order_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
   $insert_order_item->execute([$order_id, $product_id, $quantity]);
   
   // Update product stock
   $new_stock = $product['stock'] - $quantity;
   $update_stock = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
   $update_stock->execute([$new_stock, $product_id]);
  
   // Redirect to a thank you page or display a success message
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
   <title>Add product</title>
   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .headerclient {
         background-color: var(--black);
         position: sticky;
         top: 0;
         left: 0;
         right: 0;
         z-index: 1000;
      }

      .headerclient .flex {
         display: flex;
         align-items: center;
         justify-content: space-between;
      }

      .headerclient .flex .logo {
         font-size: 2.5rem;
         color: var(--main-color);
         font-weight: bolder;
      }

      .headerclient .flex .navbar a {
         padding: 1rem 1.3rem;
         background-color: var(--white);
         border-radius: .5rem;
         margin-left: 1rem;
         font-size: 2rem;
         color: var(--black);
      }

      .headerclient .flex .navbar .cart-icon span {
         margin-left: .3rem;
      }

      .headerclient .flex .navbar a:hover {
         background-color: var(--main-color);
         color: var(--white);
      }
      /* Add styles for the review form */
      .review-form {
         display: none;
         margin-top: 20px;
      }

      .review-form textarea {
         width: 100%;
         padding: 8px;
         margin-bottom: 10px;
         border: 1px solid #ccc;
         border-radius: 3px;
         box-sizing: border-box;
         resize: vertical; /* Allow vertical resizing */
      }

      .review-form input[type="submit"] {
         background-color: #4caf50;
         color: white;
         padding: 10px 20px;
         border: none;
         border-radius: 3px;
         cursor: pointer;
         width: 100%;
      }

      .review-form input[type="submit"]:hover {
         background-color: #45a049;
      }

      .review-btn {
   background-color: #3498db;
   color: #fff;
   padding: 8px 16px;
   border: none;
   border-radius: 4px;
   cursor: pointer;
   transition: background-color 0.3s ease;
}

.review-btn:hover {
   background-color: #2980b9;
}

   
   </style>

</head>
<body>
   
<!-- header section starts  -->

<!-- header section ends -->
<header class="headerclient">
    <section class="flex">
        <a href="index.php" class="logo">Chocolate Co.</a>
        <nav class="navbar">
            <?php
            // Check if user is logged in
            if (isset($_SESSION['user_id'])) {
                // User is logged in, display the cart icon
                echo '<a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>';
               
            }
            ?>
           
        </nav>
    </section>
</header>

<!-- view products section starts  -->

<section class="view-products">

   <h1 class="heading">all products</h1>
   
   

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){         
   ?>
   <div class="box">
      <form action="" method="post">
         <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
         <img src="uploaded_files/<?= $fetch_product['image']; ?>" alt="" class="image">
         <!-- Add review button -->
         <?php if($fetch_product['stock'] > 9){ ?>
            <span class="stock" style="color: green;"><i class="fas fa-check"></i> in stock</span>
         <?php }elseif($fetch_product['stock'] == 0){ ?>
            <span class="stock" style="color: red;"><i class="fas fa-times"></i> out of stock</span>
         <?php }else{ ?>
            <span class="stock" style="color: red;">hurry, only <?= $fetch_product['stock']; ?> left</span>
         <?php } ?>
         <h3 class="name"><?= $fetch_product['name']; ?></h3>
         <div class="flex">
            <p class="price"><i class="fa-solid fa-dollar-sign"></i> <?= $fetch_product['price']; ?></p>
            <input type="number" name="qty" value="1" min="1" max="99" maxlength="2" required class="qty">
         </div>
         <?php if($fetch_product['stock'] != 0){ ?>
            <input type="submit" value="Buy Now" name="buy_now" class="option-btn">
            <input type="submit" value="Add to Cart" name="add_to_cart" class="option-btn">
            <br>
            
            <button type="button" class="review-btn"  onclick="toggleReviewForm('review-form-<?= $fetch_product['id']; ?>')">Add a Review</button>
         <?php }; ?>
         
      </form>
      <!-- Review Form -->
      <form action="" method="post" class="review-form" id="review-form-<?= $fetch_product['id']; ?>">
         <input type="hidden" name="product_name" value="<?= $fetch_product['name']; ?>">
         <label for="review">Add Review:</label>
         <textarea id="review" name="review" rows="4" required></textarea>
         <input type="submit" value="Submit Review" name="submit_review">
      </form>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>

<!-- view products section ends -->

<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
   // Function to toggle visibility of review form
   function toggleReviewForm(formId) {
      var form = document.getElementById(formId);
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
   }
</script>

<?php include 'components/alers.php'; ?>

</body>
</html>