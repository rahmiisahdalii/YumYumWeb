<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['user_message'])){
   $evo_id = $_POST['evo_id'];
   $msg = $_POST['msg'];

   $insert_evo = $conn->prepare("UPDATE `product_evaluation` SET evo_message = ? , is_evo = 1 WHERE id = ?");
   $insert_evo->execute([$msg,$evo_id]);
}

function createEvaluation($product_id,$user_id,$placed_on){
   include 'components/connect.php';
   $evo_status = $conn->prepare("SELECT * FROM `product_evaluation` WHERE (product_id = ? and user_id = ? and placed_on = ?)");
   $evo_status->execute([$product_id,$user_id,$placed_on]);
   $evo = $evo_status->fetch(PDO::FETCH_ASSOC);
   $status = $evo['is_evo'];
   $evo_id = $evo['id'];

   if($status == 0){
      echo "
      <form method='POST'>
         <input type='hidden' name='evo_id' value='$evo_id' />
         <input type='text' name='msg' />
         <input class='btn' value='Değerlendir' type='submit' name='user_message' />
      </form>
      ";
   }else{
      echo "<div class='btn' style='background-color:green;'>Değerlendirme Yapılmıştır :)</div>";
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>siparisler</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>siparisler</h3>
   <p><a href="html.php">Anasayfa</a> <span> / Siparisler</span></p>
</div>

<section class="orders">

   <h1 class="title">Siparişler</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Siparişlerinizi görmek için lütfen <a href="login.php">Giriş Yapınız!</a></p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC");
         $select_orders->execute([$user_id]);

         //YAZILCAK
         //$evaluation_product = $conn->prepare("SELECT * FROM `evaluation` ");

         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" style="border-radius:20px; border:1px solid">
      <p>Sipariş Tarihi : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>İsim : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Tel No : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Adres : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Ödeme Yöntemi : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Siparişlerin : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Toplam Fiyat : <span><?= $fetch_orders['total_price']; ?> $</span></p>
      <p>Sipariş Durumu : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }elseif($fetch_orders['payment_status'] == 'onroad'){echo 'orange';}else{ echo 'green'; }; ?>"><?php if($fetch_orders['payment_status'] == 'pending'){echo 'Hazırlanıyor';}elseif($fetch_orders['payment_status'] == 'onroad'){echo 'Siparişiniz Yolda';}else{echo 'Teslim Edildi';} ?></span> </p>
      <?php 
         if($fetch_orders['payment_status'] == 'pending' || $fetch_orders['payment_status'] == 'onroad'){ 
            echo "<a href='send_message.php?product={$fetch_orders['product_id']}' class='btn'>Canlı Destek</a>";
         }
         else{
            createEvaluation($fetch_orders['product_id'],$user_id,$fetch_orders['placed_on']);
         }
         //if($fetch_orders['payment_status'] == 'pending' || $fetch_orders['payment_status'] == 'onroad'){
      ?>
   </div>
   <?php
         
      }
      }else{
         echo '<p class="empty">Henüz Sipariş Verilmedi!</p>';
      }
      }
   ?>

   </div>

</section>










<!-- footer section starts  -->

<!-- footer section ends -->






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>