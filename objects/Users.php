<?php 

class User {

    private $database_connection;
    private $username;
    private $email;
    private $password;
    private $role;

    function __construct($db) {
        $this->database_connection = $db;
    }

    function addUser($username_IN,$email_IN,$password_IN,$role_IN = "user") {
        if(!empty($username_IN) && !empty($email_IN)&& !empty($password_IN)) {

            $sql = "SELECT username, email FROM users WHERE username=:username_IN OR email=:email_IN ";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":username_IN", $username_IN);
            $stmt->bindParam(":email_IN", $email_IN);
            
            if(!$stmt->execute()) {
                echo "Couldnt execute sql!";
                die();
            } 
            $affected = $stmt->rowCount();
            if($affected > 0) {
                echo "Username or email is already registered!";
                die();
            }
            $sql = "INSERT INTO users (username, email, password, role) VALUES(:username_IN, :email_IN, :password_IN, :role_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":username_IN", $username_IN);
            $stmt->bindParam(":email_IN", $email_IN);
            $stmt->bindParam(":password_IN", $password_IN);
            $stmt->bindParam(":role_IN", $role_IN);

            if(!$stmt->execute()){
                echo "something went wrong!";
            } else {
                echo "User added!";
            }

        }
        else {
            $error = new stdClass();
                $error->message = "A user needs a username, and email and a password to be registered!";
                $error->code = "0001";
                print_r(json_encode($error));
                die();
        }

    }

}