<?php
 include('../../config/database_handler.php');
 include('../../objects/InCart.php');

 $userId =$_GET['userId'];
 $productId =$_GET['productId'];
 $cart = new Cart($pdo);
 $cart->removeFromCart($userId,$productId)
?>