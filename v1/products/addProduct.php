<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

if(isset($_GET['name']) && isset($_GET['description']) && isset($_GET['imageurl']) && isset($_GET['category']) && isset($_GET['price']) ){
    $name = $_GET['name'];
    $description = $_GET['description'];
    $imageurl = $_GET['imageurl'];
    $category = $_GET['category'];
    $price = $_GET['price'];
    $product  = new Product($pdo);
    print_r(json_encode($product->AddProduct($name,$description,$imageurl,$category,$price)));
} else  {
    $error = new stdClass();
    $error->message="Not enough specified data for addition of product!";
    $error->code="0006";
    print_r(json_encode($error));
    die();
}
?>


?>
