<?php 
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}


?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canlı Destek</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <section class="send-message">
        <?php 
            $product_id = $_GET['product'];
            $get_admin_id = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $get_admin_id->execute([$product_id]);
            $admin_of_product = 0;

            if($get_admin_id->rowCount() > 0){
                while($fetch_admin_of_product = $get_admin_id->fetch(PDO::FETCH_ASSOC)){
                    $admin_of_product = $fetch_admin_of_product['admin_id'];
                }
            }    
            
?>
           
            <div class='chat-container' id='chat-conn'>
                
                <?php
                    $all_messages = $conn->prepare("SELECT * FROM messages WHERE (product_id = ? and (sender_id = ? or reciever_id = ?)) ORDER BY message_date ASC");
                    $all_messages->execute([$product_id,$user_id,$user_id]);
                    if($all_messages->rowCount() > 0){
                        while($fetch_all_messages = $all_messages->fetch(PDO::FETCH_ASSOC)){
                ?> 
                    
                        <?php 
                            $msg = $fetch_all_messages['message'];
                            if($fetch_all_messages['sender_id'] == $user_id){
                                echo 
                                "<script>
                                    var messageElement = document.createElement('div');
                                    messageElement.classList.add('message', 'sender');
                                    messageElement.textContent = '$msg';
                    
                                    

                                    //var chatBox = document.getElementById('chat-box');
                                    //chatBox.appendChild(messageElement);
                                    
                                    var chatBox = document.createElement('div');
                                    chatBox.setAttribute('id', 'chat-box');
                                    chatBox.setAttribute('class', 'chat-box');
                                    chatBox.appendChild(messageElement);

                                    var chatContainer = document.getElementById('chat-conn');
                                    chatContainer.appendChild(chatBox);
                                </script>";
                            }else{
                                echo 
                                "<script>
                                    var messageElement = document.createElement('div');
                                    messageElement.classList.add('message', 'reciever');
                                    messageElement.textContent = '$msg';
                    
                                    //var chatBox = document.getElementById('chat-box');
                                    //chatBox.appendChild(messageElement);

                                    var chatBox = document.createElement('div');
                                    chatBox.setAttribute('id', 'chat-box');
                                    chatBox.setAttribute('class', 'chat-box');
                                    chatBox.appendChild(messageElement);

                                    var chatContainer = document.getElementById('chat-conn');
                                    chatContainer.appendChild(chatBox);
                                </script>";
                            }
                        ?>
                <?php
                    }
                }
                ?>
            </div>
            <div class='input-box'>
                        <input type='text' id='message-input' name='send-msg' placeholder='Mesajınızı yazın...'>
                        <button id='send-button'>Gönder</button>
                </div>

        
    </section>

    <!-- footer section starts  -->
    <!-- footer section ends -->

    <script src="js/script.js"></script>
    <script>
        document.getElementById('send-button').addEventListener('click', sendMessage);
        document.getElementById('message-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const messageText = messageInput.value.trim();

            if (messageText !== '') {
                var messageElement = document.createElement('div');
                messageElement.classList.add('message', 'sender');
                messageElement.textContent = messageText;

                var chatBox = document.createElement('div');
                chatBox.setAttribute('id', 'chat-box');
                chatBox.setAttribute('class', 'chat-box');

                chatBox.appendChild(messageElement);
                chatBox.scrollTop = chatBox.scrollHeight;

                var chatContainer = document.getElementById('chat-conn');
                chatContainer.appendChild(chatBox);

                messageInput.value = '';
                messageInput.focus();

                var productid = <?php echo json_encode($product_id); ?>;
                var adminid = <?php echo json_encode($admin_of_product); ?>;
                var userid = <?php echo json_encode($user_id); ?>;
                insertMessage(userid,adminid,productid,messageText);
            }
        }

        function insertMessage(user,admin,product,mesg){

            const formData = new URLSearchParams();
            formData.append('message', mesg);
            formData.append('user_id', user);
            formData.append('admin_id', admin);
            formData.append('product_id', product);

            fetch('insert_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Sunucudan gelen yanıtı konsola yaz
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>