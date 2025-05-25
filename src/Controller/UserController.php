<?php

namespace Controller;

use Model\User;
use Model\Dao\UserDao;
use Model\Dao\OrderDao;
use Model\Dao\ProductDao;

class UserController{
    public function login(){
	$email = '';
	$pass = '';
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    if(isset($_POST['register_btn'])){
		// Register
		$email = htmlentities(trim($_POST['email']));
		$pass = htmlentities(trim($_POST['pass']));

		$user = new User();
		$user->setEmail($email);
		$user->setPass($pass);

		$userDao = new UserDao();
		$userDao->create($user);
	    }else{
		//Login
		$email = htmlentities(trim($_POST['email']));
		$pass = htmlentities(trim($_POST['pass']));

		$userDao = new UserDao();
		$user = $userDao->findByEmail($email);

		if($user && password_verify($pass, $user->getPass())){
		    $_SESSION['email'] = $user->getEmail();
		    $_SESSION['role'] = $user->getRole();
		    header('Location: index.php?target=home&action=index');
		    die();
		} else{
		    $error = "Invalid email or password";
		    require_once "../src/View/users/login.php";
		}
	    }

	    
	    
	}else{
	    require_once "../src/View/users/login.php";
	}

    }

    public function profile(){
	$isLogged = isset($_SESSION['email']);
	$userDao = new UserDao();
	$user = $userDao->findByEmail($_SESSION['email']);
	$error = '';
	$success = '';
	if(!$isLogged){
	    header("Location: index.php?target=user&action=login");
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    // logout
	    if(isset($_POST['logout_btn'])){
		session_destroy();
		header("Location: index.php");
	    }

	    // change pass
	    if(isset($_POST['change_pass_btn'])){
		$old_pass = htmlentities(trim($_POST['oldPass']));
		$new_pass = htmlentities(trim($_POST['newPass']));
		
		if(empty($old_pass) || empty($new_pass)){
		    $error = 'Both fields are required, password was not changed.';
		}else{		    
		    if(password_verify($old_pass, $user->getPass())){
			$user->setPass($new_pass);
			$userDao->updatePass($user);
			$success = "Password has been changed successfully.";
		    }else{
			$error = "Wrong password, password was not changed.";
		    }
		}
	    }
	}

	// orders
	$productDao = new ProductDao();
	$orderDao = new OrderDao($productDao);
	$orders = $orderDao->getAllOrdersOfUser($user->getId());
	

	

	require_once "../src/View/users/profile.php";
    }
}