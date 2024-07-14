<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}


function getNameFromDb($nameid,$sql) {
   include '../components/connect.php';
   $res = $conn->prepare($sql);
   $res->execute([$nameid]);

   $result = $res->fetch(PDO::FETCH_ASSOC);
   return $result['name'];
}

function checkProductStatus($product_id,$user_id){
   include '../components/connect.php';
   $res = $conn->prepare("SELECT * FROM `orders` WHERE (product_id = ? and user_id = ?) ORDER BY id DESC LIMIT 1;");
   $res->execute([$product_id,$user_id]);

   $result = $res->fetch(PDO::FETCH_ASSOC);
   return $result['payment_status'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>mesajlar</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- messages section starts  -->

<section class="messages">

   <h1 class="heading">mesajlar</h1>

   <div class="box-container">

   <?php
      //$select_messages = $conn->prepare("SELECT * FROM `messages` WHERE reciever_id = ?");
      $get_all_sender_id = $conn->prepare("SELECT sender_id, product_id FROM messages WHERE reciever_id = ? GROUP BY sender_id, product_id;");
      $get_all_sender_id->execute([$admin_id]);
      if($get_all_sender_id->rowCount() > 0){
         while($fetch_messages = $get_all_sender_id->fetch(PDO::FETCH_ASSOC)){
   ?>
    <div class="box" style ="border-radius: 20px; border: 1px">
      <p> Gönderen : <span><?= getNameFromDb($fetch_messages['sender_id'],"SELECT name FROM `users` WHERE id = ?") ?></span> </p>
      <p> Ürün : <span><?= getNameFromDb($fetch_messages['product_id'],"SELECT name FROM `products` WHERE id = ?") ?></span> </p>
      <?php 
         if(checkProductStatus($fetch_messages['product_id'],$fetch_messages['sender_id']) != 'completed'){
            $user_name = getNameFromDb($fetch_messages['sender_id'],"SELECT name FROM `users` WHERE id = ?");
            $product_name = getNameFromDb($fetch_messages['product_id'],"SELECT name FROM `products` WHERE id = ?");
            echo "<a href='message_page.php?product={$fetch_messages['product_id']}&user_id={$fetch_messages['sender_id']}&user_name={$user_name}&product_name={$product_name}' class='btn'>Mesaja Git</a>";
         }
      ?>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty" style= "border-radius: 20px; border:1px ;">Henüz Mesajınız Yok</p>';
      }
   ?>

   </div>

</section>

<!-- messages section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>