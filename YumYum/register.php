<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
    $select_user->execute([$email, $number]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message[] = 'E-posta veya numara zaten mevcut!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Şifreler eşleşmiyor!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
            $insert_user->execute([$name, $email, $number, $cpass]);
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if ($select_user->rowCount() > 0) {
                $_SESSION['user_id'] = $row['id'];
                header('location:home.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kayit Ol</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="register.php" method="post">
      <h3>Hemen Kaydol</h3>
      <input type="text" name="name" required placeholder="Adinizi girin" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="E-posta adresinizi girin" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Telefon numaranizi girin" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Sifrenizi girin" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Sifrenizi tekrar girin" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Hemen Kaydol" name="submit" class="btn">
      <p>Zaten bir hesabiniz var mi? <a href="login.php">Simdi giris yapin</a></p>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
