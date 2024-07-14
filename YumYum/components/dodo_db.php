<?php
    $conn = new mysqli("localhost", "root", "1234", "yumrum");
    $result = '';
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if (isset($_GET['get_kategori'])) {
        $cat = kategori_getir($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_id"])) {
        $category_id = intval($_POST["category_id"]);
        $get_name_of_category = getNameOfCategory($category_id,$conn);
        $result = rastgele_siparis_getir($conn, $get_name_of_category);
    }


function kategori_getir($conn) {
    $sql = "SELECT * FROM `category` ORDER BY `id` ASC";
    $result = $conn->query($sql);
    

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<form method="POST" action="">' .
                 '<input type="hidden" name="category_id" value="' . $row["id"] . '">' .
                 '<button class="cat_button">' . $row["id"] . ' - ' . htmlspecialchars($row["name"]) . '</button>' .
                 '</form><br>';
        }
    } else {
        echo "0 results";
    }
}

function getNameOfCategory($category_id, $conn) {
    $sql = $conn->prepare("SELECT name FROM category WHERE id = ?");
    
    if ($sql === false) {
        die('Error preparing the SQL statement: ' . htmlspecialchars($conn->error));
    }
    
    $sql->bind_param("i", $category_id);
    
    if (!$sql->execute()) {
        die('Error executing the SQL statement: ' . htmlspecialchars($sql->error));
    }
    
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        $category_row = $result->fetch_assoc();
        $category_name = $category_row['name'];
    } else {
        $category_name = null; 
    }
    
    $result->free();
    $sql->close();
    
    return $category_name;
}

function rastgele_siparis_getir($conn, $category_name) {
    $sql = "
            SELECT orders.id, orders.name    
            FROM orders 
            JOIN products ON (orders.product_id = products.id and products.category = ?)
            ORDER BY RAND()
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return '<p>Bugünkü Önerdiğimiz Ürün: ' . $row["name"] . '</p>';
    } else {
        return '<p>Bu kategoriden daha önce hiç deneyimlemedin. Şimdi tam zamanı :)</p>';
    }

    $stmt->close();
}

?>