<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Restoranlar</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .restaurants {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         gap: 20px;
         padding: 20px;
      }

      .restaurants .box-container{
         width: 100%;
         display: flex;
         flex-wrap: wrap;
         margin: 0 auto;
         justify-content: center;
      }

      .box {
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         width: 300px;
         border: 1px solid #ccc;
         padding: 20px;
         box-sizing: border-box;
         text-align: center;
         margin-bottom: 20px;
      }

      .name {
         font-weight: bold;
         margin-bottom: 10px;
         font-size: 24px;
      }

      .box img {
         width: 100%;
         height: auto;
         margin-bottom: 10px;
         border-radius: 8px;
      }
   </style>
</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>restoranlar</h3>
   <p><a href="home.php">Anasayfa</a> <span> / Restoranlar</span></p>
</div>

<!-- Restaurant list section starts  -->

<section class="restaurants">

   <h1 class="title">restoranlarimiz</h1>

   <div class="box-container">

      <?php
        $select_restaurants = $conn->prepare("SELECT * FROM admin WHERE su != 1");

         $select_restaurants->execute();
         if($select_restaurants->rowCount() > 0){
            while($fetch_restaurant = $select_restaurants->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box" style="border-radius:10px; margin:20px; ">
         <img src="uploaded_img/<?= $fetch_restaurant['resim']; ?>" alt="<?= $fetch_restaurant['name']; ?>">
         <div class="name"><?= $fetch_restaurant['name']; ?></div>
         <a href="menu.php?admin_id=<?= $fetch_restaurant['id']; ?>" class="btn">Menüyü Gör</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">Restoran Bulunamadı!</p>';
         }
      ?>

   </div>

</section>

<!-- Restaurant list section ends -->

<!-- footer section starts  -->

<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
