<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){
   
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ? AND admin_id = ?");
   $update_status->execute([$payment_status, $order_id, $admin_id]);
   $message[] = 'Durum Güncellendi!';

   if($payment_status == 'completed'){
      $res = $conn->prepare("SELECT product_id, user_id, placed_on FROM `orders` WHERE id = ?");
      $res->execute([$order_id]);
      $product = $res->fetch(PDO::FETCH_ASSOC);
      $produc_id = $product['product_id'];
      $user_id = $product['user_id'];
      $placed_on = $product['placed_on'];

      deleteOldMessage($produc_id,$user_id);
      createEvaluation($produc_id,$admin_id,$user_id,$placed_on);
   }

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ? AND admin_id = ?");
   $delete_order->execute([$delete_id, $admin_id]);
   header('location:placed_orders.php');
}

function deleteOldMessage($product_id,$user_id){
   include '../components/connect.php';

   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE (product_id = ? and (sender_id = ? or reciever_id = ?))");
   $delete_message->execute([$product_id,$user_id,$user_id]);
}

function createEvaluation($product_id,$admin_id,$user_id,$placed_on){
   include '../components/connect.php';

   $create_evo = $conn->prepare("INSERT INTO product_evaluation(product_id, admin_id, user_id, placed_on) VALUES (?,?,?,?)");
   $create_evo->execute([$product_id,$admin_id,$user_id,$placed_on]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>verilen siparisler</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed orders section starts  -->

<section class="placed-orders">

   <h1 class="heading">verilen siparisler</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");

      // Yeni koşul: Eğer adminin özelliği "su" ise, tüm siparişleri getir
      if ($admin_id != 1) {
          $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE admin_id = ? ORDER BY placed_on DESC");
          $select_orders->execute([$admin_id]);
      } else {
          $select_orders->execute();
      }

      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" style ="border-radius: 30px; border: 1px">
      <p> Kullanici id : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Sipariş Tarihi : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Sipariş adı : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Numara : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Adres : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Toplam urunler : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Toplam fiyat : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Ödeme yontemi : <span><?= $fetch_orders['method']; ?></span> </p>
      
      <?php if($admin_id != 1){ ?>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">Hazırlanıyor</option>
            <option value="onroad">Yola Çıktı</option>
            <option value="completed">Teslim Edildi</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="update" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </div>
      </form>
      <?php } ?>
      
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Henüz Sipariş Verilmedi!</p>';
   }
   ?>

   </div>

</section>

<!-- placed orders section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
