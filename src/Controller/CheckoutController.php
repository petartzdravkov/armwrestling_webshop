<?php

namespace Controller;

use Model\Dao\ProductDao;
use Controller\ProductController;
use Exception;

class CheckoutController{

    public function index(){

	$isUserLogged = $this->isUserLogged();
	$product_controller = new ProductController;
	$productDao = new ProductDao;
	$pdo = $productDao->getPdoConnection();
	$cart_items = $product_controller->getCartItemsFromSession();
	$total = $product_controller->calculateCartTotal($cart_items);
	$payment_error = '';
	
	// when pay now is pressed
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_btn'])){
	    try{
		$pdo->beginTransaction();
		
		foreach($cart_items as $key => $amount){
		    [$id, $size] = explode("|", $key);
		    
		    $cart_item = $productDao->getProductById($id);
		    $sizes_in_stock = $productDao->getAmountByIdAndSize($id, $size);

		    // check if there is enough stock of this item and size
		    if($sizes_in_stock < $amount){
			throw new Exception("Not enough stock for '" . $cart_item->getName() . "', size '" . $size . "'.");
		    }

		    $productDao->decreaseStock($id, $size, $amount);   
		}

		$pdo->commit();
		header("Location: index.php?target=checkout&action=success");
		die;
	    }catch (Exception $e){
		$pdo->rollback();
		$payment_error = $e->getMessage();
	    }


	    // header("Location: index.php?target=checkout&action=success");
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