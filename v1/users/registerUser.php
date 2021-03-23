<?php 
include('../../config/database_handler.php');
include('../../objects/Users.php');

$username = $_GET['username'];
$email = $_GET['email'];
$password = $_GET['password'];
$user = new User($pdo);
$user->addUser($username,$email,$password)
?>
