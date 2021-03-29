<?php 
include('../../config/database_handler.php');
include('../../objects/Products.php');

if(isset($_GET['category'])){
    $category = $_GET['category'];
    $product = new Product($pdo);
    print_r(json_encode($product->ShowByCat($category)));
} else {
    $error = new stdClass();
    $error->message="Category not specified!";
    $error->code="0002";
    print_r(json_encode($error));
    die();
}

?>