<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


function getNameWithId($name,$sql){
   include 'components/connect.php';

   $get_name = $conn->prepare($sql);
   $get_name->execute([$name]);

   $result = $get_name->fetch(PDO::FETCH_ASSOC);
   return anonymize_name($result['name']);
}

function anonymize_name($name) {
   if (strlen($name) <= 1) {
       return $name; // Eğer isim tek harfse, anonimleştirmeye gerek yok
   }
   $first_char = $name[0];
   $stars = str_repeat('*', strlen($name) - 1);
   return $first_char . $stars;
}

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Hızlı bakış</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="title">Hızlı bakış</h1>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_products['price']; ?></div>
         <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
      </div>
      <button type="submit" name="add_to_cart" class="cart-btn">Sepete Ekle</button>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Henüz Bir Ürün Eklenmedi!</p>';
      }
   ?>

   <div class="quick-wiev-container">
      <h1>Ürün Değerlendirmeleri</h1>
        

      <?php
         $product_id = $_GET['pid']; 
         $get_all_evo = $conn->prepare("SELECT * FROM `product_evaluation` WHERE (product_id = ? and is_evo = 1) ORDER BY placed_on DESC");
         $get_all_evo->execute([$product_id]);

         if($get_all_evo->rowCount() > 0){
            while($fetch_evo = $get_all_evo->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="review">
            <h2><?= getNameWithId($fetch_evo['user_id'],"SELECT name FROM `users` WHERE id = ?"); ?></h2>
            <p class="date"><?= $fetch_evo['placed_on']; ?></p>
            <p style="font-size:16px;"><?= $fetch_evo['evo_message']; ?></p>
         </div>
      <?php

            }
         }else{
            echo '<p class="empty">Ürüne Henüz Bir Değerlendirme Yapılmamış!</p>';
         }
      ?>
      </div>

</section>
















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>