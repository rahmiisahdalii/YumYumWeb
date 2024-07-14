<?php include 'dodo_db.php' ?>
<section>
    <style>
        .kategori{
        display: flex;
        width: 50px;
        height: 50px;
        position: fixed;
        bottom: 20px;
        right: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 50%;
    }
    .siparis{
        position: fixed; 
        right: 20px; 
        bottom: 300px;
    }
    .dodo-form{
        display: flex; 
        opacity: 1;
        display: flex;
        flex-direction: column;
        p{
            font-size: 16px;
        }
    }
    #dodo-items{
        display: none;
        overflow: hidden;
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 300px;
        height: auto;
        background-color: rgb(105, 185, 105);
        align-items: center;
        flex-direction: column;
        border-radius: 10px;
        gap: 16px;
    }
    
    #dodo-btn{
        width: 100%;
        padding: 6px;
        background-color: red;
        border: none;
        cursor: pointer;
        transition: 0.5s ease-in;
    }
    
    #dodo-btn:hover{
        background-color: rgb(165, 14, 14);
    }
    
    #dodo_all_categories{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    #dodo_all_categories .form{
        display: flex;
        height: 100%;
        flex-direction: column;
    }
    
    .result_for_dodo{
        width: 100%;
        height: 100%;
        background-color: rgb(232, 222, 204);
        text-align: center;
        border: 1px solid orange;
        background-color: rgb(34, 148, 34);
        color: orange;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        padding: 4px 4px;
        p{
            padding: 0 2px;
        }
    }
    
    .dodo-button{
        padding:0; 
        width: 100%;
        height: 100%;
        border: none;
        background-color: rgb(22, 199, 81);
        color: #fff;
        cursor: pointer;
        border-radius: 10px;
    }
    
    .cat_button{
        width: 100%;
        height: auto;
        background-color: white;
        cursor: pointer;
        border: none;
        font-weight: 700;
        padding: 5px;
        transition: 0.4s ease;
        border-radius: 10px;
        color: black;
    }
    .cat_button:hover{
        background-color: orange;
    }
    a{
        color: #fff;
        text-align: center;
        opacity: 1;
        text-decoration: none;
    } 
    </style>
    <div class="kategori" id="kategoriler">
        <button type="button" onclick="showCart()" class="dodo-button">DODO</button>
        
    </div>
    <div id="dodo-items">
        <button id="dodo-btn" onclick="closeCart()">Kapat</button>
        <div id="dodo_all_categories">
            <div class="dodo-form">
                <p>Hangi Kategoriden Yemek İstersin?</p>
                <?php 
                    $conn = new mysqli("localhost", "root", "1234", "yumrum");
                    kategori_getir($conn);
                ?>
            </div>
        </div>
        <div class="result_for_dodo">
            <p><?= $result; ?></p>
        </div>
    </div>

    <script type="text/javascript">
        var dodo = document.getElementById("kategoriler");
        var cart = document.getElementById("dodo-items");
        function showCart(){
            cart.style.display = "flex";
        }
        function closeCart(){
            cart.style.display = "none";
        }
    </script>
</section>