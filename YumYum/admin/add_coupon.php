<?php 
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['add_coupon'])){
    $coupon_name = $_POST['coupon_name'];
    $coupon_type = $_POST['coupon_type'];
    $coupon_amount = $_POST['coupon_amount'];
    $coupon_end_date = $_POST['coupon_end_date'];
    $coupon_min_price = $_POST['coupon_min_price'];

    $add_coupon = $conn->prepare("INSERT INTO coupons(admin_id, coupon_string, type, amount, end_date, min_price) VALUES (?,?,?,?,?,?)");
    $add_coupon->execute([$admin_id,$coupon_name,$coupon_type,$coupon_amount,$coupon_end_date,$coupon_min_price]);
    
    $message[] = 'Kuponunuz Tanımlandı';
    header('location:dashboard.php');
}

if(isset($_GET['delete_coupon'])){
    $delete_coupon_id = $_GET['delete_coupon'];
    $delete_coupon = $conn->prepare("DELETE FROM `coupons` WHERE id = ?");
    $delete_coupon->execute([$delete_coupon_id]);
    $message[] = 'Kupon Silindi!';
    header('location:dashboard.php');
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupon İşlemleri</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    <?php include '../components/admin_header.php' ?>

    <section class="add-products">
        <h1 style="text-align:center;">Tanımlı Tüm Kuponlar</h1>

        <form action="" method="POST">
            <h3>Kupon Tanımla</h3>
            <input type="text" required placeholder="Kupon Adı" name="coupon_name" maxlength="100" class="box" id="coupon_type">
            <select name="coupon_type" class="box" required>
                <option value="percent">Yüzdelik</option>
                <option value="numeric">Sayısal</option>
            </select>
            <input type="number" required placeholder="Kupon Miktarı" min="0" max="100" name="coupon_amount" id="coupon_amount" class="box">
            <input type="number" value="0" min="0" max="100"  name="coupon_min_price" placeholder="Minimum Tutar Var Mı?" class="box">
            <input type="date" name="coupon_end_date" class="box" required>
            <input type="submit" value="Kupon Ekle" name="add_coupon" class="btn">
        </form>

    </section>
    <section class="placed-orders" style="padding-top: 0;">
        <div class="box-container">
            <?php
                if($admin_id == 1){
                    $get_coupons = $conn->prepare("SELECT * FROM `coupons`");
                    $get_coupons->execute();    
                }else{
                    $get_coupons = $conn->prepare("SELECT * FROM `coupons` WHERE admin_id = ?");
                    $get_coupons->execute([$admin_id]);    
                }
                if($get_coupons->rowCount() > 0){
                    while($fetch_coupon = $get_coupons->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="box">
                <p>Kupon: <span><?= $fetch_coupon['coupon_string']; ?></p>
                <p>Kupon Miktarı: <span style="font-weight:bold;color:orange;"><?php if($fetch_coupon['type'] == "percent"){echo "%{$fetch_coupon['amount']} İNDİRİM";}else{echo "{$fetch_coupon['amount']}$ İNDİRİM";} ?></p>
                <p>Bitiş Günü: <span><?= $fetch_coupon['end_date']; ?></p>
                <p>Minimum Fiyat: <span><?= $fetch_coupon['min_price']; ?></p>
                <form action="" method="POST">
                    <input type="hidden" name="coupon_id" value="<?= $fetch_coupon['id']; ?>">
                    <?php
                        if ($admin_id == $fetch_coupon['admin_id']) {
                            $coupon_id = $fetch_coupon['id'];
                            echo "<a href='add_coupon.php?delete_coupon=$coupon_id' class='delete-btn' onclick='return confirm(\"Kuponu Silmek İstediğinizden Emin Misiniz?\");'>Sil</a>";
                        }
                    ?>
                </form>
            </div>
            <?php 
                    }
                }
                
            ?>
        </div>
    </section>           
    <script src="../js/admin_script.js"></script>

</body>
</html>