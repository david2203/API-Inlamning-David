<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

$name = $_GET['name'];
$description = $_GET['description'];
$imageurl = $_GET['imageurl'];
$category = $_GET['category'];
$price = $_GET['price'];
$product  = new Product($pdo);
$product->addProduct($name,$description,$imageurl,$category,$price);

?>
