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
    if($user->IsTokenValid($token)) {
        if(isset($_GET['userId']) & isset($_GET['productId']) & isset($_GET['quantity'])){
        $productId = $_GET['productId'];
        $userId = $_GET['userId'];
        $quantity = $_GET['quantity'];
        print_r(json_encode($cart->ProductToUsercart($productId,$userId,$token,$quantity)));
        } else {
            $error = new stdClass();
            $error->message = "Product id or User id or Quantity not specified";
            $error->code = "0002";
            echo json_encode($error);
            die();
        }
        
    
    } else {
        $error = new stdClass();
        $error->message = "Invalid token! Login to create a new token.";
        $error->code = "0010";
        print_r(json_encode($error));
    }



?>