<?php 
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['approve_restorant'])){
    $demand_id = $_POST['demand_id'];

    //talep sayfasındaki satırı aldık
    $get_demand_info = $conn->prepare("SELECT * FROM `restaurant_demand` WHERE id = ?");
    $get_demand_info->execute([$demand_id]);
    $get_demand_row = $get_demand_info->fetch(PDO::FETCH_ASSOC);

    //içindeki değerleri okuduk
    $name = $get_demand_row['username'];
    $image = $get_demand_row['restaurant_image'];
    $pass = $get_demand_row['password'];

    //talep tablosundaki statusu 1 yaptık
    $update_status = $conn->prepare("UPDATE `restaurant_demand` SET status = 1 WHERE id = ?");
    $update_status->execute([$demand_id]);
    

    //statusu 1 olan talebi adminler tablosuna ekledik
    $add_to_admin = $conn->prepare("INSERT INTO admin(name, password, su, resim)
                                    VALUES (?,?,2,?)");
    $add_to_admin->execute([$name,$pass,$image]);
    header('location:dashboard.php');
}

if(isset($_GET['delete_demand'])){
    $demand_id = $_GET['delete_demand'];
    $delete_demans = $conn->prepare("DELETE FROM `restaurant_demand` WHERE id = ?");
    $delete_demans->execute([$demand_id]);
    header('location:dashboard.php');
 }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Talepleri</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include '../components/admin_header.php' ?>

    <section class="placed-orders">
        <div class="box-container">
            <?php 
                $get_demand = $conn->prepare("SELECT * FROM `restaurant_demand` WHERE status = 0");
                $get_demand->execute();

                if($get_demand->rowCount() > 0){
                    while($fetch_demand = $get_demand->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="box">
                <p>Restoran Adı: <span><?= $fetch_demand['username']; ?></p>
                <p>Restoran Açıklaması: <span><?= $fetch_demand['restaurant_info']; ?></p>
                <form action="" method="POST">
                    <input type="hidden" name="demand_id" value="<?= $fetch_demand['id']; ?>">
                    <input type="submit" class="btn" value="Onayla" name="approve_restorant">
                    <a href="restaurant_demand.php?delete_demand=<?= $fetch_demand['id'] ?>" class="delete-btn" onclick="return confirm('Talebi Silmek istediğinize emin misiniz?');">Sil</a>
                </form>
            </div>
            <?php 
                    }
                }else{
                    echo '<p class="empty">Restoran Talebi Bulunmamaktadır!</p>';
                }
            ?>
        </div>
    </section>


    <script src="../js/admin_script.js"></script>

</body>
</html>