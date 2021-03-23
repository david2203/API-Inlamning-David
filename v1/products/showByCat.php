<?php 
include('../../config/database_handler.php');
include('../../objects/Products.php');

$category = $_GET['category'];
$product = new Product($pdo);
print_r($product->showByCat($category));
?>