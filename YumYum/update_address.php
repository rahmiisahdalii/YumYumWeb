<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){

   $address = $_POST['flat'] .', '.$_POST['building'].', '.$_POST['area'].', '.$_POST['town'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'address saved!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>adres guncelle</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post" style="border-radius:10%; border:1px solid">
      <h3>adresleriniz</h3>
      <input type="text" class="box" placeholder="Adres Başlığı" required maxlength="50" name="state">
      <input type="text" class="box" placeholder="Daire Numaranız" required maxlength="50" name="flat">
      <input type="text" class="box" placeholder="Bina Numaranız." required maxlength="50" name="building">
      <input type="text" class="box" placeholder="Mahalle Adı" required maxlength="50" name="area">
      <input type="text" class="box" placeholder="Sokak ve Cadde" required maxlength="50" name="town">
      <input type="text" class="box" placeholder="Şehir Adı" required maxlength="50" name="city">
      <input type="text" class="box" placeholder="Ülke Adı" required maxlength="50" name="country">
      <input type="number" class="box" placeholder="Posta Kodu" required max="999999" min="0" maxlength="6" name="pin_code">
      <input type="submit" value="Adresi Kaydet" name="submit" class="btn">
   </form>

</section>


















<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>