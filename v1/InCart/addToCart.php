<?php
include('../../config/database_handler.php');
include('../../objects/InCart.php');

$productId = $_GET['productId'];
$userId = $_GET['userId'];
$quantity = $_GET['quantity'];
$cart = new Cart($pdo);
$cart->productToUsercart($productId,$userId,$quantity);

?>