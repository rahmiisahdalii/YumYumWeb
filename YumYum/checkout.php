<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $product_id = '';

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      while($cart = $check_cart->fetch(PDO::FETCH_ASSOC)){
         $name = $cart['name'];
         $total_products = "{$cart['name']} ({$cart['price']} x {$cart['quantity']})";
         $total_price = ($cart['price'] * $cart['quantity']);
         $product_id = $cart['pid'];
         $admin_id = $cart['admin_id'];

         $query = "INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, admin_id, product_id) 
         VALUES (:user_id, :name, :number, :email, :method, :address, :total_products, :total_price, :admin_id, :product_id)";
         
         $sql = $conn->prepare($query);
         $sql->execute([
            ':user_id' => $user_id,
            ':name' => $name,
            ':number' => $number,
            ':email' => $email,
            ':method' => $method,
            ':address' => $address,
            ':total_products' => $total_products,
            ':total_price' => $total_price,
            ':admin_id' => $admin_id,
            ':product_id' => $product_id
         ]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ? AND pid = ?");
         $delete_cart->execute([$user_id,$product_id]);

      }
      $message[] = 'Siparişiniz Başarıyla Oluşturuldu!';
      header('location:orders.php');
   }
   else{
      $message[] = 'Sepetiniz Boş!';
   }
}

$coupon_code_status = 0;

if (isset($_GET['coupon'])) {
   $coupon_code = $_GET['coupon'];
   
   $check = $conn->prepare("SELECT * FROM `coupons` WHERE coupon_string = ?");
   $check->execute([$coupon_code]);
   $coupon_row = $check->fetch(PDO::FETCH_ASSOC);

   $get_user_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $get_user_cart->execute([$user_id]);

   if($get_user_cart->rowCount() > 0 && $check->rowCount() > 0){
      $admin_of_coupon = $coupon_row['admin_id'];
      $coupon_amount_on_row = $coupon_row['amount'];
      $coupon_type_on_row = $coupon_row['type'];
      $coupon_end_on_row = $coupon_row['end_date'];
      $coupon_min_price_on_row = $coupon_row['min_price'];
      
      while($fetch_user_cart = $get_user_cart->fetch(PDO::FETCH_ASSOC)){
         $admin_of_product = $fetch_user_cart['admin_id'];
         $cart_id_of_user = $fetch_user_cart['id'];
         $product_price_of_user = $fetch_user_cart['price'];
         $product_quantity_of_user = $fetch_user_cart['quantity'];
         

         //echo "{$admin_of_product} - {$cart_id_of_user} - {$total_price_of_user}";
         if($admin_of_product == $admin_of_coupon){
            $result = updateCartWithCoupon($cart_id_of_user,$product_price_of_user, $product_quantity_of_user, $coupon_type_on_row,$coupon_amount_on_row,$coupon_end_on_row,$coupon_min_price_on_row);
         }
      }
      $message[] = $result;
      if($result == 'Kupon Başarıyla Tanımlandı :)'){
         $coupon_code_status = 1;
      }
      
   }else{
      $message[] = 'Kupon Geçerli Değil!';
   }

   
}

function updateCartWithCoupon($cart_id,$product_price,$product_quantity,$coupon_type,$coupon_amount,$coupon_end_time,$coupon_min_price){
   include 'components/connect.php';

   $current_date = new DateTime();
   $timestamp_date = new DateTime($coupon_end_time);

   //kupon hala geçerli mi kontrol
   if($current_date < $timestamp_date){
      $check_price_with_coupon = calculateCoupon($product_price,$coupon_type,$coupon_amount,$product_quantity);
      $total_amount = $product_price * $product_quantity;
      if($total_amount < $coupon_min_price){
         $missing_price = $coupon_min_price - $total_amount;
         $re = "Lütfen Sepetinize {$missing_price}$ Daha Ürün Ekleyin";
         return $re;
      }else{
         $price_with_coupon = calculateCoupon($product_price,$coupon_type,$coupon_amount,$product_quantity);
         $update_cart = $conn->prepare("UPDATE cart SET price = ? WHERE id = ?");
         $update_cart->execute([$price_with_coupon,$cart_id]);
      
         return 'Kupon Başarıyla Tanımlandı :)';
      }  
   }
   else{
      return 'Kupon Tarihi Geçerli Değil!!';
   }
}

function calculateCoupon($total_price,$coupon_type,$coupon_amount,$product_quantity){
   if($coupon_type == "numeric"){
      $coupon_amount /= $product_quantity;
      $total_price -= $coupon_amount;
   }else{
      $total_price -= ($total_price * $coupon_amount) / 100; 
   }
   return $total_price;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>odeme</title>
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
   <h3>Ödeme</h3>
   <p><a href="home.php">Anasayfa</a> <span> / Ödeme</span></p>
</div>

<section class="checkout">

   <h1 class="title">Siparis ozeti</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>Sepet bilgileri</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">Sepetiniz Boş!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">Toplam :</span><span class="price">$<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">Sepet goruntule</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
   <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

   <div class="user-info">
      <h3>Bilgileriniz</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <h3>Teslimat adres</h3>
      <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'Lütfen Adresinizi Giriniz!';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">Adres guncelle</a>
      <select name="method" class="box" required>
         <option value="" disabled selected>Ödeme yontemi sec --</option>
         <option value="cash on delivery">Nakit odeme</option>
         <option value="credit card">Kapıda kredi karti</option>
      </select>
      <input type="submit" value="Siparişi Tamamla" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
   </div>
</form>
<form action="" method="GET" style="margin-top:8px;">
      <?php if($coupon_code_status == 0){ ?>
         <input type="text" id="coupon" name="coupon" maxlength="100" class="box" placeholder="Kuponunuz Varsa Giriniz">
         <br>
         <button type="submit" class="btn">Kuponu Kontrol Et</button>
      <?php }else{ ?>
         <div style="background-color:green;" class="btn">İndiriminiz Uygulanmıştır. </div>
      <?php } ?>
</form>
</section>

<!-- footer section starts  -->
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>
