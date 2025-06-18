<?php

namespace Controller;

use Model\Dao\ProductDao;
use Controller\ProductController;
use Exception;
use Model\Dao\OrderDao;
use Model\Order;
use Model\Dao\UserDao;

class CheckoutController{

    public function index(){

	// get user id
	$isUserLogged = $this->isUserLogged();
	if($isUserLogged){
	    $userDao = new UserDao();
	    $user_id = $userDao->getUserIdByEmail($_SESSION['email']);
	}else{
	    $user_id = null;
	}


	// get cart items
	$product_controller = new ProductController;
	$cart_items = $product_controller->getCartItemsFromSession();
	$total = $product_controller->calculateCartTotal($cart_items);

	// get productDao for view
	$productDao = new ProductDao;

	// init payment error
	$payment_error = '';

	// when pay now is pressed
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_btn'])){
	    $order = new Order($cart_items, $user_id);
	    $orderDao = new OrderDao($productDao);
	    $orderDao->save($order);

	    header("Location: index.php?target=checkout&action=success");
	    die();
	}

	
	require_once "../src/View/checkout/index.php";
    }

    public function success(){
	// TODO implement subtraction of products from DB

	$_SESSION['cart'] = [];
	require_once "../src/View/checkout/success.php";
    }
    
    private function isUserLogged(){
	$isUserLogged = isset($_SESSION['email']) ? true : false;
	return $isUserLogged;
    }

    





}