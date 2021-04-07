<?php

class Product {

    private $database_connection;
    

    function __construct($db) {
        $this->database_connection = $db;
    }

    function AddProduct($name_IN,$description_IN,$image_IN,$category_IN,$price_IN) {
        if(!empty($name_IN)&& !empty($description_IN)&& !empty($image_IN)&& !empty($category_IN)&& !empty($price_IN)){

            $sql = "SELECT name,description,image,category,price FROM products WHERE name = :name_IN AND description = :description_IN AND image = :image_IN AND category = :category_IN AND price = :price_IN ";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":name_IN", $name_IN);
            $stmt->bindParam(":description_IN", $description_IN);
            $stmt->bindParam(":image_IN", $image_IN);
            $stmt->bindParam(":category_IN", $category_IN);
            $stmt->bindParam(":price_IN", $price_IN);
            $stmt->execute();
            $response = new stdClass();
            if($stmt->rowCount() > 0) {
                $response->text = "This product is already added!";
                return $response;
            }

            $sql = "INSERT INTO products (name,description,image,category,price) VALUES(:name_IN, :description_IN, :image_IN, :category_IN, :price_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":name_IN", $name_IN);
            $stmt->bindParam(":description_IN", $description_IN);
            $stmt->bindParam(":image_IN", $image_IN);
            $stmt->bindParam(":category_IN", $category_IN);
            $stmt->bindParam(":price_IN", $price_IN);

            if($stmt->execute()) {
                $response->text = "Product added!";
                return $response;
            } else {
                $error = new stdClass();
                    $error->message ="Couldnt create post!";
                    $error->code="0008";
                    print_r(json_encode($error));
                    die();
            }
            
        } else {
            $error = new stdClass();
                $error->message="Not enough specified data to add a product";
                $error->code="0006";
                print_r(json_encode($error));
                die();
        }

    }

    function DeleteProduct($productId) {

        $sql = "SELECT productId FROM cart WHERE productId = :productId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productId_IN", $productId);
        $stmt->execute();

            if($stmt->rowCount() > 0) {
                $error = new stdClass();
                    $error->message = "This product cant be deleted because its added by a user to their shoppingcart!";
                    $error->code="0011";
                    print_r(json_encode($error));
                    die();
            }

        $sql = "DELETE FROM products WHERE id = :productId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productId_IN", $productId);
        $stmt->execute();

        
        
        if($stmt->rowCount() > 0) {
            $response = new stdClass();
                $response->text = "Product with id $productId removed!";
                return $response;
        }
        else {
            $error = new stdClass();
                $error->message = "No product with id=$productId was found!";
                $error->code = "0003";
                print_r(json_encode($error));
                die();   
        }     

    }
    
    function ListProducts() {
        $sql = "SELECT name, description, image, category, price FROM products";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function ShowSpecific($productId) {
        $sql = "SELECT name, description, image, category, price FROM products WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $productId);
        $stmt->execute();

        if($stmt->rowCount()>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->image = $row['image'];
            $this->category = $row['category'];
            $this->price = $row['price'];

            return $row;
        } else {
            $error = new stdClass();
                $error->message = "Id not found!";
                $error->code = "0003";
                echo json_encode($error);
                die();
        }
      
    }

    function EditProduct($id, $name ="", $description = "", $image = "", $category = "", $price = "") {
 
        $sql = "SELECT name,description,image,category,price FROM products WHERE name = :name_IN OR description = :description_IN OR image = :image_IN OR category = :category_IN OR price = :price_IN ";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":name_IN", $name);
            $stmt->bindParam(":description_IN", $description);
            $stmt->bindParam(":image_IN", $image);
            $stmt->bindParam(":category_IN", $category);
            $stmt->bindParam(":price_IN", $price);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $error = new stdClass();
                $error->message = "Atleast one of the 'to be edited' data is not new!";
                $error->code = "0012";
                echo json_encode($error);
                die();
               
            }


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

    function ShowByCat($category_IN){
        $sql = "SELECT * FROM products WHERE category LIKE :category_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":category_IN", $category_IN);
        $stmt->execute();
        $productCount = $stmt->rowCount();
        if($productCount < 1) {
            
            $error = new stdClass();
            $error->message = "No product with this category found!";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($row);
    }
}