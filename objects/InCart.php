<?php

class Cart {

    private $database_connection;


    function __construct($db){
        $this->database_connection = $db;
    }

    function productToUsercart($productId_IN,$userId_IN,$quantity_IN) {

        $sql = "SELECT * FROM products WHERE id = :productId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productId_IN", $productId_IN);
        $stmt->execute();
        $productCount = $stmt->rowCount();
        if($productCount = 0) {
            echo "Tere is no product with this id in our database!";
            die();
        }

        $sql = "INSERT INTO cart (productId,userId,quantity,orderdate) VALUES(:productId_IN, :userId_IN, :quantity_IN, NOW())";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productId_IN",$productId_IN);
        $stmt->bindParam(":userId_IN",$userId_IN);
        $stmt->bindParam(":quantity_IN",$quantity_IN);
        
        if(!$stmt->execute()) {
            echo "couldnt execute!";
            die();
        }
        echo " $quantity_IN x of the product with id $productId_IN was added to your cart!";

    }

    function removeFromCart($userId_IN, $productId_IN){
        $sql = "DELETE FROM cart WHERE userId = :userId_IN AND productId = :productId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":userId_IN", $userId_IN);
        $stmt->bindParam(":productId_IN", $productId_IN);

        if(!$stmt->execute()) {
            echo "didnt work";
            die();
        }
        
        echo "Product with id $productId_IN was removed from your cart!";
    }
    





}