<?php
include('../../config/database_handler.php');
include('../../objects/InCart.php');
include('../../objects/Users.php');


$token = "";
if(isset($_GET['token'])){
    $token = $_GET['token'];
}
else {
    $error = new stdClass();
    $error -> message = "No token specified!";
    $error -> code = "0005";
    print_r(json_encode($error));
    die();
}

$user = new User($pdo);
$cart = new Cart($pdo);
    if($user->isTokenValid($token)) {
        $productId = $_GET['productId'];
        $userId = $_GET['userId'];
        $quantity = $_GET['quantity'];
        print_r(json_encode($cart->productToUsercart($productId,$userId,$token,$quantity)));
    
    } else {
        $error = new stdClass();
        $error->message = "Invalid token! Login to create a new token.";
        $error->code = "0010";
        print_r(json_encode($error));
    }



?>