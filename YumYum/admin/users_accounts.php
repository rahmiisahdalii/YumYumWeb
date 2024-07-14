<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kullanici hesaplari</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- user accounts section starts  -->

<section class="accounts">

   <h1 class="heading">kullanici hesaplari</h1>

   <div class="box-container">

   <?php
      if($admin_id == 1){
         $select_account = $conn->prepare(
            "SELECT u.id, u.name, u.email, u.number, COUNT(o.id) AS number_of_orders
            FROM users u
            JOIN orders o ON u.id = o.user_id
            GROUP BY u.id, u.name, u.email, u.number"
         );
         $select_account->execute();
      }else{
         $select_account = $conn->prepare(
            "SELECT u.id, u.name, u.email, u.number, COUNT(o.id) AS number_of_orders
            FROM users u
            JOIN orders o ON u.id = o.user_id
            WHERE o.admin_id = $admin_id
            GROUP BY u.id, u.name, u.email, u.number"
         );
         $select_account->execute();
      }
      
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
   ?>
    <div class="box" style ="border-radius: 20px; border: 1px">
      <p> Kullanıcı Adı : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> Kullanıcı Mail : <span><?= $fetch_accounts['email']; ?></span> </p>
      <p> Kullanıcı Telefon No : <span><?= $fetch_accounts['number']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no accounts available</p>';
   }
   ?>

   </div>

</section>

<!-- user accounts section ends -->







<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>