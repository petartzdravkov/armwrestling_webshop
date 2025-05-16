<?php

namespace Controller;

use Model\User;
use Model\Dao\UserDao;

class UserController{
    public function login(){
	$email = '';
	$pass = '';
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
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

	    
	    
	}else{
	    require_once "../src/View/users/login.php";
	}

    }

    public function register(){
	$email = '';
	$pass = '';
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    $email = htmlentities(trim($_POST['email']));
	    $pass = htmlentities(trim($_POST['pass']));

	    $user = new User();
	    $user->setEmail($email);
	    $user->setPass($pass);

	    $userDao = new UserDao();
	    $userDao->create($user);
	    
	    
	}else{
	    require_once "../src/View/users/register.php";
	}
    }

    public function profile(){
	if(isset($_SESSION['email'])){
	    require_once "../src/View/users/profile.php";	    
	}else{
	    header("Location: index.php?target=user&action=login");
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    if(isset($_POST['logout_btn'])){
		session_destroy();
		header("Location: index.php");
	    }
	}
    }
}