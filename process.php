<?php
session_start();
require_once('connection.php');
require_once('functions.php');
// var_dump($_POST['birthdate']);

if(isset($_POST['action']) && $_POST['action'] == 'register')
	{
    	register($connection, $_POST);
    }
else if (isset($_POST['action']) && $_POST['action'] == 'login')
    {
    	login($connection, $_POST);
    }  
else if (isset($_GET['logout']))
    {
    	logout();
    }      

if (!isset($_SESSION['error']) && !isset($_SESSION['login_error'])) 
	{

		$salt = bin2hex(openssl_random_pseudo_bytes(22));
		$hash = crypt($_POST['password'], $salt);

		$query = "INSERT INTO users (first_name, last_name, email, password, updated_at, created_at)
				  VALUES ('" . mysqli_real_escape_string($connection, $_POST['first_name']) . "', '" . mysqli_real_escape_string($connection, $_POST['last_name']) . "', '" . mysqli_real_escape_string($connection, $_POST['email']) . "', '" . $hash . "', NOW(), NOW())";
		$_SESSION['message'] = $_SESSION['first_name'] . ' your account was created successfully!';
		mysqli_query($connection, $query);

		$user_id = mysqli_insert_id($connection);

		$_SESSION['user_id'] = $user_id;

		header('Location: profile.php?id=' .$user_id);
		exit;
	}
	// mysqli_real_escape_string($connection, 
?>