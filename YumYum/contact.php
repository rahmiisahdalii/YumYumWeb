<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['create_demand'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   //$image = $_POST['image'];
   //$image = filter_var($image, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);


   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploads/'.$image;

   if($image_size > 2000000){
      $message[] = 'Resim boyutu çok büyük!';
   }else{
      move_uploaded_file($image_tmp_name, $image_folder);

      $insert_demand = $conn->prepare("INSERT INTO restaurant_demand(username, password, restaurant_image, restaurant_info)
                                    VALUES (?,?,?,?)");
      $insert_demand->execute([$name, $pass, $image, $msg]);

      $message[] = 'Talebiniz başarıyla oluşturuldu!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>iletisim</title>

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
   <h3>Restoran Talebinde Bulunun</h3>
   <p><a href="home.php">Anasayfa</a> <span> / İletisim</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="POST" enctype="multipart/form-data">
         <h3>Talep Bilgileri</h3>
         <input type="text" name="name" maxlength="50" class="box" placeholder="Lütfen Bir Firma Kullanici Adi Giriniz" required>
         <input type="password" name="pass" min="0" max="9999999999" class="box" placeholder="Lütfen Bir Şifre Tuşlayınız" required maxlength="10">
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
         <textarea name="msg" class="box" required placeholder="Restoran Bilgilerinizi giriniz" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="Talep Oluştur" name="create_demand" class="btn">
      </form>

   </div>

</section>

<!-- contact section ends -->










<!-- footer section starts  -->

<!-- footer section ends -->








<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>