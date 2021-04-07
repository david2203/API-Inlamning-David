<?php 
//class to be accesed by userhandeling-endpoints
class User {

    private $database_connection;
    

    function __construct($db) {
        $this->database_connection = $db;
    }

    function AddUser($username_IN,$email_IN,$password_IN,$role_IN = "user") {
        if(!empty($username_IN) && !empty($email_IN)&& !empty($password_IN)) {

            $sql = "SELECT username, email FROM users WHERE username=:username_IN OR email=:email_IN ";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":username_IN", $username_IN);
            $stmt->bindParam(":email_IN", $email_IN);
            
            if(!$stmt->execute()) {
                $error = new stdClass();
                    $error -> message = "Sql failed to execute!";
                    $error -> code = "0006";
                    print_r(json_encode($error));
                    die();
            } 
            $affected = $stmt->rowCount();
            if($affected > 0) {
                $error = new stdClass();
                    $error -> message = "Username or email already exist!";
                    $error -> code = "0007";
                    print_r(json_encode($error));
                    die();
            }
            $sql = "INSERT INTO users (username, email, password, role) VALUES(:username_IN, :email_IN, :password_IN, :role_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":username_IN", $username_IN);
            $stmt->bindParam(":email_IN", $email_IN);
            $salt1 = "aGsdf45L";
            $salt2 = "Suasg25R";
            $kryptPass = md5($salt1.$password_IN.$salt2);
            $stmt->bindParam(":password_IN", $kryptPass);
            $stmt->bindParam(":role_IN", $role_IN);

             if($stmt->execute()){
                $response =  new stdClass();
                    $response->text= "User added!";
                    return $response;
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

    function LoginUser($username_IN,$password_IN) {
        $sql = "SELECT id, username, email, role FROM users WHERE username=:username_IN AND password=:password_IN";
        $stmt=$this->database_connection->prepare($sql);
        $stmt->bindParam(":username_IN",$username_IN);
        $salt1 = "aGsdf45L";
        $salt2 = "Suasg25R";
        $kryptPass = md5($salt1.$password_IN.$salt2);
        $stmt->bindParam(":password_IN",$kryptPass);

        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            return $this->CreateToken($row['id'],$row['username']);
        } else {
            $error = new stdClass();
                $error->message = "Wrong username or password!";
                $error->code = "0004";
                echo json_encode($error);
                die();
        }

    }

    function CreateToken($id, $username) {

        $checked_token = $this->checkToken($id);

        if($checked_token != false) {
            return $checked_token;
        }
            $token = md5(time() . $id . $username);

            $sql = "INSERT INTO sessions (userId, token, last_used) VALUES(:userId_IN, :token_IN, :last_used_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":userId_IN",$id);
            $stmt->bindParam(":token_IN",$token);
            $time = time();
            $stmt->bindParam(":last_used_IN",$time);

            $stmt->execute();

            return $token;

    }

    function CheckToken($id) {
        $sql = "SELECT token, last_used FROM sessions WHERE userId=:userId_IN AND last_used > :active_time_IN";
        $stmt =$this->database_connection->prepare($sql);
        $stmt->bindParam(":userId_IN",$id);
        $active_time = time() - (60*60);
        $stmt->bindParam(":active_time_IN", $active_time);

        $stmt->execute();

        $return = $stmt->fetch();

        if(isset($return['token'])){
            return $return['token'];
        }
        else {
            return false;
        }
    }

    function IsTokenValid($token) {
        $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN";
        $stmt =$this->database_connection->prepare($sql);
        $stmt->bindParam(":token_IN",$token);
        $active_time= time() - (60*60);
        $stmt->bindParam(":active_time_IN",$active_time);

        $stmt->execute();

        $return = $stmt->fetch();

        if(isset($return['token'])){
            $this->UpdateToken($return['token']);

            return true;
        }
        else {
            return false;
        }
    }

    function UpdateToken($token) {
        $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
        $stmt = $this->database_connection->prepare($sql);
        $time = time();
        $stmt->bindParam(":last_used_IN", $time);
        $stmt->bindParam(":token_IN", $token);
        $stmt->execute();
    }

    
}