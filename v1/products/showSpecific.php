<?php 
include("../../config/database_handler.php");
include("../../objects/Products.php");

$product = new Product($pdo);

if(!empty($_GET['id'])) {
    echo json_encode($product->showSpecific($_GET['id']));

} else {
    $error = new stdClass();
    $error->message = "Id not specified";
    $error->code = "0002";
    echo json_encode($error);
    die();
}