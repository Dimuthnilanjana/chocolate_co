<?php




session_start();
include 'components/connect.php';

// Handle delete action
if(isset($_POST['delete_product'])) {
    $delete_id = $_POST['delete_id'];
    $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $success_msg[] = 'Product Deleted!';
}

// Handle update action
if(isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $new_price = $_POST['new_price'];
    $new_stock = $_POST['new_stock'];
    
    $update_product = $conn->prepare("UPDATE products SET price = ?, stock = ? WHERE id = ?");
    $update_product->execute([$new_price, $new_stock, $update_id]);
    $success_msg[] = 'Product Updated!';
}

if(isset($_POST['add_product'])){
   $name = $_POST['name'];
   $price = $_POST['price'];
   $stock = $_POST['stock'];
   $image = $_FILES['image']['name'];
   $image_tmp = $_FILES['image']['tmp_name'];

   // Move uploaded image to destination directory
   move_uploaded_file($image_tmp, "uploaded_files/$image");

   // Insert new product into the database
   $insert_product = $conn->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
   $insert_product->execute([$name, $price, $stock, $image]);

   // Display success message
   $success_msg[] = 'Product Added!';
}


// Fetch all products
$get_products = $conn->prepare("SELECT * FROM products");
$get_products->execute();
$products = $get_products->fetchAll();
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
      /* Table Styles */
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }

      th, td {
         padding: 8px;
         text-align: left;
         border-bottom: 1px solid #ddd;
      }

      th {
         background-color: #f2f2f2;
      }

      tr:hover {
         background-color: #f5f5f5;
      }

      img {
         max-width: 100px;
         height: auto;
      }

      .action-btn {
         background-color: #dc3545;
         color: white;
         border: none;
         padding: 5px 10px;
         cursor: pointer;
      }

      .action-btn:hover {
         background-color: #bb2d3b;
      }

      /* Additional CSS for update form */
      .update-form {
         display: none;
      }

      /* CSS for buttons */
      .btn {
         padding: 5px 10px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
      }
      .delete-btn {
         background-color: #ff3333;
         color: #fff;
      }
      .update-btn {
         background-color: #3399ff;
         color: #fff;
      }


      
   </style>

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- add product section starts  -->

<section class="add-product">

   <h1 class="heading">add product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>product details</h3>
      <p>product name <span>*</span></p>
      <input type="text" name="name" required maxlength="50" placeholder="enter product name" class="input">
      <p>product price <span>*</span></p>
      <input type="number" name="price" required maxlength="10" placeholder="enter product price" min="0" max="9999999999" class="input">
      <p>total stock <span>*</span></p>
      <input type="number" name="stock" required maxlength="10" placeholder="total products available" min="0" max="9999999999" class="input">
      <p>product image <span>*</span></p>
      <input type="file" required name="image" accept="image/*" class="input">
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

   <!-- Display added products -->
   <h2 class="heading">Products</h2>
   <table>
      <thead>
         <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($products as $product): ?>
            <tr>
               <td><?php echo $product['id']; ?></td>
               <td><?php echo $product['name']; ?></td>
               <td><?php echo $product['price']; ?></td>
               <td><?php echo $product['stock']; ?></td>
               <td><img src="uploaded_files/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"></td>
               <td>
                  <form action="" method="post" >
                     <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                     <input class="button-24" type="submit" name="delete_product" value="Delete">
                  </form>
                  <button class="button-25" onclick="toggleUpdateForm(<?php echo $product['id']; ?>)">Update</button>
               </td>
            </tr>
            <!-- Update form for each product -->
            <tr class="update-form" id="update-form-<?php echo $product['id']; ?>">
               <td colspan="5">
                  <form action="" method="post">
                     <input type="hidden" name="update_id" value="<?php echo $product['id']; ?>">
                     <p>New Price</p>
                     <input type="number" name="new_price" required maxlength="10" placeholder="enter products price" min="0" max="9999999999" class="input">
                     <p>New Stock</p>
                     <input type="number" name="new_stock" required maxlength="10" placeholder="total products available" min="0" max="9999999999" class="input">
                     <input type="submit" name="update_product" value="Update">
                  </form>
               </td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</section>

<!-- add product section ends -->

<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/alers.php'; ?>

<script>
   // Function to toggle visibility of update form
   function toggleUpdateForm(productId) {
      var form = document.getElementById('update-form-' + productId);
      form.classList.toggle('update-form');
   }
</script>

</body>
</html>
