<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kontrol paneli</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">kontrol paneli</h1>

   <div class="box-container">

   <div class="box" style ="border-radius: 20px; border: 1px">
      <h3>Hosgeldiniz</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">profili guncelle</a>
   </div>

   <?php if($admin_id != 1){ ?>
      <div class="box" style ="border-radius: 20px; border: 1px">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? AND admin_id = ?");
         $select_pendings->execute(['pending',$admin_id]);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
         }
      ?>
      <h3><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
      <p>Toplam Bekleyenler</p>
      <a href="placed_orders.php" class="btn">siparisleri gor</a>
   </div>
   <?php } ?>

   <div class="box" style ="border-radius: 20px; border: 1px">
      <?php
         if($admin_id == 1){
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
         }else{
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? and admin_id = ?");
            $select_completes->execute(['completed',$admin_id]);
         }
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes += $fetch_completes['total_price'];
         }
      ?>
      <h3><span>$</span><?= $total_completes; ?><span>/-</span></h3>
      <p>Toplam Tamamlanan</p>
      <a href="placed_orders.php" class="btn">Siparisleri gor</a>
   </div>

   <div class="box" style ="border-radius: 20px; border: 1px">
      <?php
         if($admin_id==1){
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();   
         }else{
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE admin_id = ?");
            $select_orders->execute([$admin_id]);
            $numbers_of_orders = $select_orders->rowCount();   
         }
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>Toplam Siparişler</p>
   </div>

   <div class="box" style ="border-radius: 20px; border: 1px">
      <?php

         if($admin_id == 1){
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();   
         }else{
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE admin_id = $admin_id");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         }
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>Restorandaki Ürünler</p>
      <a href="products.php" class="btn">Ürünleri Gör</a>
   </div>

   <div class="box" style ="border-radius: 20px; border: 1px">
      <?php
         if($admin_id == 1){
            $select_users = $conn->prepare("SELECT COUNT(DISTINCT user_id) AS number_of_users FROM orders");
            $select_users->execute();
            $numbers_of_users = $select_users->fetchColumn();
         }else{
            $select_users = $conn->prepare("SELECT COUNT(DISTINCT user_id) AS number_of_users FROM orders WHERE admin_id = $admin_id");
            $select_users->execute();
            $numbers_of_users = $select_users->fetchColumn();
         }
         
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>Sipariş Veren Kullanıcılar</p>
      <a href="users_accounts.php" class="btn">Kullanıcıları Gör</a>
   </div>

   <div class="box" style="<?php if($admin_id != 1){echo 'display:none;';} ?>">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admin`");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>Adminler</p>
      <a href="admin_accounts.php" class="btn">Adminleri Gor</a>
   </div>

   <?php if($admin_id != 1){ ?>
      <div class="box" style ="border-radius: 20px; border: 1px">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $numbers_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>Mesajlar</p>
      <a href="messages.php" class="btn">Mesajlari gor</a>
   </div>
   <?php } ?>

   <?php if($admin_id == 1){ ?>
      <div class="box" style ="border-radius: 20px; border: 1px">
         <?php
            $select_messages = $conn->prepare("SELECT COUNT(*) AS demand_count FROM `restaurant_demand` WHERE status = 0");
            $select_messages->execute();
            $res = $select_messages->fetch(PDO::FETCH_ASSOC);
            $number_of_demand = $res['demand_count'];
         ?>
         <h3><?= $number_of_demand; ?></h3>
         <p>Restoran Talepleri</p>
         <a href="restaurant_demand.php" class="btn">Talepleri Gör</a>
      </div>
   <?php } ?>

   <div class="box" style ="border-radius: 20px; border: 1px">
         <?php
            if($admin_id == 1){
               $select_coupons = $conn->prepare("SELECT COUNT(*) AS all_coupons FROM `coupons`");
               $select_coupons->execute();
               $res = $select_coupons->fetch(PDO::FETCH_ASSOC);

            }else{
               $select_coupons = $conn->prepare("SELECT COUNT(*) AS all_coupons FROM `coupons` WHERE admin_id = ?");
               $select_coupons->execute([$admin_id]);
               $res = $select_coupons->fetch(PDO::FETCH_ASSOC);
            }
            $number_of_coupons = $res['all_coupons'];
         ?>
         <h3><?= $number_of_coupons ?></h3>
         <p>Aktif Kuponlar </p>
         <a href="add_coupon.php" class="btn">Kupon Ekle</a>
      </div>
   </div>

</section>

<!-- admin dashboard section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>