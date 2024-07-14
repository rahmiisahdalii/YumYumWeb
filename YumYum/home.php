<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Anasayfa</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="hero">
      <div class="swiper hero-slider">

         <div class="swiper-wrapper">

         <div class="swiper-slide slide">
               <div class="content">
               <span>Online Siparis</span>
                  <h3>HAMBURGER</h3>
                  <a href="menu.php" class="btn">menuyu gor</a>
               </div>
               <div class="image">
                  <img src="images/home-img-2.png" alt="">
               </div>
            </div>


            <div class="swiper-slide slide">
               <div class="content">
                  <span>Online Siparis</span>
                  <h3>PİZZA</h3>
                  <a href="menu.php" class="btn">menuyu gor</a>
               </div>
               <div class="image">
                  <img src="images/home-img-1.png" alt="">
               </div>
            </div>

           
            <div class="swiper-slide slide">
               <div class="content">
               <span>Online Siparis</span>
                  <h3>KIZARMIŞ TAVUK</h3>
                  <a href="menu.php" class="btn">menuyu gor</a>
               </div>
               <div class="image">
                  <img src="images/home-img-3.png" alt="">
               </div>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <section class="category">

      <h1 class="title">YEMEK KATEGORİLERİ</h1>

      <div class="box-container">

         <a href="category.php?category=fast food" class="box" style="border-radius:20%;">
            <img src="images/cat-1.png" alt="">
            <h3>fast food</h3>
         </a>

         <a href="category.php?category=main dish" class="box" style="border-radius:20%;">
            <img src="images/cat-2.png" alt="">
            <h3>ana yemekler</h3>
         </a>

         <a href="category.php?category=drinks" class="box" style="border-radius:20%;">
            <img src="images/cat-3.png" alt="İçecek">
            <h3>icecekler</h3>
         </a>

         <a href="category.php?category=desserts" class="box" style="border-radius:20%;">
            <img src="images/cat-4.png" alt="">
            <h3>tatlilar</h3>
         </a>

      </div>

   </section>

   <?php 
   if(isset($_SESSION['user_id'])){
      include 'components/dodo.php';
   }
?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".hero-slider", {
         loop: true,
         grabCursor: true,
         effect: "flip",
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });
   </script>

</body>

</html>