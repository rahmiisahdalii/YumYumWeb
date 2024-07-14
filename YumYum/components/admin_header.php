<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>
<?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            
<header class="header">

    <section class="flex">

        <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="dashboard.php">Anasayfa</a>
            <a href="products.php">Ürünler</a>
            <a href="placed_orders.php">Siparisler</a>
            <?php
            // Sadece su değeri 1 olan adminler için "admins" bağlantısını görüntüle
            if ($fetch_profile['su'] == 1) {
                echo '<a href="admin_accounts.php">Adminler</a>';
            }
            ?>
            <?php
            // Sadece su değeri 1 olan adminler için "users" bağlantısını görüntüle
            if ($fetch_profile['su'] == 1) {
                echo '<a href="users_accounts.php">Kullanıcılar</a>';
            }
            ?>
            <a href="messages.php">Mesajlar</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            
            <a href="update_profile.php" class="btn">Profil guncelle</a>
            <div class="flex-btn">
                <?php
                // Check if the user is an admin with value 1
                if ($fetch_profile['su'] == 1) {
                    echo '<a href="register_admin.php" class="option-btn">register</a>';
                }
                ?>
            </div>
            <a href="../components/admin_logout.php" onclick="return confirm('Çıkmak istediğinize emin misiniz?');" class="delete-btn">Çıkış yap</a>
        </div>

    </section>

</header>
