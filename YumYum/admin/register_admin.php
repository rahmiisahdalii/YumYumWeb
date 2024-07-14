<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Resim işlemleri
   $target_dir = "../uploads/";
   $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
   $uploadOk = 1;
   $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
   // Resmi kontrol etmek
   $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
   if($check !== false) {
      $uploadOk = 1;
   } else {
      $message[] = "Dosya bir resim değil.";
      $uploadOk = 0;
   }
   // Dosya var mı kontrol et
   if (file_exists($target_file)) {
      $message[] = "Üzgünüz, dosya zaten mevcut.";
      $uploadOk = 0;
   }
   // Dosya boyutu kontrol et
   if ($_FILES["fileToUpload"]["size"] > 500000) {
      $message[] = "Üzgünüz, dosyanız çok büyük.";
      $uploadOk = 0;
   }
   // İzin verilen dosya formatları kontrol et
   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
   && $imageFileType != "gif" ) {
      $message[] = "Üzgünüz, sadece JPG, JPEG, PNG & GIF dosyaları yükleyebilirsiniz.";
      $uploadOk = 0;
   }
   // Dosya yüklenebilir mi kontrol et
   if ($uploadOk == 0) {
      $message[] = "Üzgünüz, dosyanız yüklenemedi.";
   // Her şey yolundaysa yükle
   } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
         $message[] = "Dosyanız başarıyla yüklendi.";
         $resim_yolu = '/uploads/' . basename( $_FILES["fileToUpload"]["name"]);
         // Veritabanına resim yolunu ekleyin
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password, resim) VALUES(?,?,?)");
         $insert_admin->execute([$name, $cpass, $resim_yolu]);
      } else {
         $message[] = "Üzgünüz, dosya yüklenirken bir hata oluştu.";
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kayit</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- register admin section starts  -->

<section class="form-container">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>yeni kayit</h3>
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" maxlength="20" required placeholder="confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="file" name="fileToUpload" id="fileToUpload" required>
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register admin section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
