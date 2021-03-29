<?php 
include("../../config/database_handler.php");
include("../../objects/Products.php");



$name = "";
$description = "";
$image = "";
$category = "";
$price = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "Id not specified";
    $error->code = "0002";
    echo json_encode($error);
    die();
}

if(isset($_GET['name'])) {
    $name = $_GET['name'];
}
if(isset($_GET['description'])) {
    $description = $_GET['description'];
}
if(isset($_GET['image'])) {
    $image = $_GET['image'];
}
if(isset($_GET['category'])) {
    $category = $_GET['category'];
}
if(isset($_GET['price'])) {
    $price = $_GET['price'];
}

$product = new Product($pdo);
echo json_encode($product->EditProduct($id,$name,$description,$image,$category,$price));
?>