<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

$product  = new Product($pdo);
$product->addProduct("name","description","imageurl","category","100");

?>
