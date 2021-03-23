<?php

class Product {

    private $database_connection;
    private $name;
    private $description;
    private $image;
    private $category;
    private $price;

    function __construct($db) {
        $this->database_connection = $db;
    }

    function addProduct($name_IN,$description_IN,$image_IN,$category_IN,$price_IN) {
        if(!empty($name_IN)&& !empty($description_IN)&& !empty($image_IN)&& !empty($category_IN)&& !empty($price_IN)){

            $sql = "INSERT INTO products (name,description,image,category,price) VALUES(:name_IN, :description_IN, :image_IN, :category_IN, :price_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":name_IN", $name_IN);
            $stmt->bindParam(":description_IN", $description_IN);
            $stmt->bindParam(":image_IN", $image_IN);
            $stmt->bindParam(":category_IN", $category_IN);
            $stmt->bindParam(":price_IN", $price_IN);

            if(!$stmt->execute()) {
                echo "Could not create post!";
            }

            
        } else {
            $error = new stdClass();
                $error->message = "A product needs a Name, description, image category and a price to be created!";
                $error->code = "0001";
                print_r(json_encode($error));
                die();
        }

    }

    function deleteProduct($productId) {
        $sql = "DELETE FROM products WHERE id = :productId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productId_IN", $productId);
        $stmt->execute();

        
        $response = new stdClass();
                if($stmt->rowCount() > 0) {
                    $response->text = "Product with id $productId removed!";
                return $response;

        
    }

        $response->text = "No product with id=$productId was found!";
                return $response;

    }
    
    function listProducts() {
        $sql = "SELECT name, description, image, category, price FROM products";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function showSpecific($productId) {
        $sql = "SELECT name, description, image, category, price FROM products WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $productId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->image = $row['image'];
            $this->category = $row['category'];
            $this->price = $row['price'];

            return $row;
    }

    function editProduct($id, $name ="", $description = "", $image = "", $category = "", $price = "") {
        $error = new stdClass();
            
        if(!empty($name)) {
            $error->message = $this->UpdateName($id, $name);
        }
        if(!empty($description)) {
            $error->message = $this->UpdateDescription($id, $description);
        }
        if(!empty($image)) {
            $error->message = $this->Updateimage($id, $image);
        }
        if(!empty($category)) {
            $error->message = $this->Updatecategory($id, $category);
        }
        if(!empty($price)) {
            $error->message = $this->UpdatePrice($id, $price);
        }
        
       return $error;
    }

    function UpdateName($id, $name) {
        $sql = "UPDATE products SET name = :name_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN",$id);
        $stmt->bindParam(":name_IN",$name);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfull edit!";
        }
    }
    function UpdateDescription($id, $description) {
        $sql = "UPDATE products SET description = :description_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN",$id);
        $stmt->bindParam(":description_IN",$description);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfull edit!";
        }
    }
    function UpdateImage($id, $image) {
        $sql = "UPDATE products SET image = :image_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN",$id);
        $stmt->bindParam(":image_IN",$image);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfull edit!";
        }
    }
    function UpdateCategory($id, $category) {
        $sql = "UPDATE products SET category = :category_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN",$id);
        $stmt->bindParam(":category_IN",$category);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfull edit!";
        }
    }
    function UpdatePrice($id, $price) {
        $sql = "UPDATE products SET price = :price_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN",$id);
        $stmt->bindParam(":price_IN",$price);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfull edit!";
        }
    }

    function showByCat($category_IN){
        $sql = "SELECT * FROM products WHERE category LIKE :category_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":category_IN", $category_IN);
        $stmt->execute();
        $productCount = $stmt->rowCount();
        if($productCount = 0) {
            echo "No products of this category found!";
            die();
        }
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($row);
    }
}