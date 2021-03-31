<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

    $product = new Product($pdo);
    $products = $product->ListProducts();
    print_r(json_encode($products)); 

?>
    