<?php 
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

$message = $_POST['message'];
$user_id = $_POST['user_id'];
$admin_id = $_POST['admin_id'];
$product_id = $_POST['product_id'];

$insert_message = $conn->prepare("INSERT INTO `messages`( `sender_id`, `reciever_id`, `product_id`, `message`) VALUES (?,?,?,?)");
$insert_message->execute([$user_id,$admin_id,$product_id,$message]);

?>