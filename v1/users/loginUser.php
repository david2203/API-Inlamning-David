<?php 
include('../../config/database_handler.php');
include('../../objects/Users.php');

$username = $_GET['username'];
$password = $_GET['password'];

$user = new User($pdo);
$return = new stdClass();
$return->token = $user->login($username, $password);
print_r(json_encode($return));
