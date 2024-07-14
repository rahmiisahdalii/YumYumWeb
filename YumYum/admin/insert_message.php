<?php 

include '../components/connect.php';

session_start();

if(isset($_SESSION['admin_id'])){
   $admin_id = $_SESSION['admin_id'];
}else{
   $admin_id = '';
}

$message = $_POST['message'];
$user_id = $_POST['user_id'];
$admin_id = $_POST['admin_id'];
$product_id = $_POST['product_id'];

$insert_message = $conn->prepare("INSERT INTO `messages`( `sender_id`, `reciever_id`, `product_id`, `message`) VALUES (?,?,?,?)");
$insert_message->execute([$admin_id,$user_id,$product_id,$message]);


?>