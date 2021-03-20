<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

    $product = new Product($pdo);

    $products = $product->listProducts();

    print_r(json_encode($products));