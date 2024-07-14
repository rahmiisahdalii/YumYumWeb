<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header" style="border:none">

   <section class="flex">

      <a href="home.php" class="logo">Yum-Yum 😋</a>

      <nav class="navbar">
         <a href="home.php">Anasayfa</a>
         <a href="about.php">Hakkımızda</a>
         <a href="restoran.php">Restoranlar</a>
         <a href="orders.php">Siparişler</a>
         <a href="contact.php">İletişim</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('Çıkmak istediğinize emin misin?');" class="delete-btn">cikis yap</a>
         </div>
         
         <?php
            }else{
         ?>
            <p class="name">Lütfen önce giriş yapınız!!!</p>
            <a href="login.php" class="btn">Giriş</a>
         <?php
          }
         ?>
      </div>

   </section>

</header>

