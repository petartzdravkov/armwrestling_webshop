<?php

namespace Controller;

use Model\Dao\ProductDao;
use Controller\ProductController;

class CheckoutController{

    public function index(){
	$isUserLogged = $this->isUserLogged();
	$product_controller = new ProductController;
	$productDao = new ProductDao;
	$cart_items = $product_controller->getCartItemsFromSession();
	$total = $product_controller->calculateCartTotal($cart_items);
	
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