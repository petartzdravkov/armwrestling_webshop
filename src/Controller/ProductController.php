<?php

namespace Controller;

use Model\Dao\ProductDao;

class ProductController{

    // view clothing
    public function clothing(){
	$this->showProducts('clothing');

    }

    // view equipment
    public function equipment(){
	$this->showProducts('equipment');	
    }

    private function showProducts($category){
	$productDao = new ProductDao();


	if($category === 'clothing'){
	    $title = 'Apparel';
	}else{
	    $title = 'Equipment';
	}
	$title = $category === 'clothing' ? 'Apparel' : 'Equipment';
	$products = $productDao->getAllProducts($category); //array with product objects

	require_once "../src/View/products/index.php";
    }

    // view individual product
    public function view(){
	$error_msg = '';
	$og_pname = htmlentities(trim($_GET['pname']));
	$pname = str_replace("-", " ", $og_pname);

	$productDao = new ProductDao();
	$product = $productDao->getProductByName($pname);
	$available_sizes = $productDao->getAllSizesByProductId($product->getId());

	// save product and amount to session if 'add to cart' is pressed
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    if(empty($_POST['selected_size'])){
		$error_msg = "Please select a size.";
	    }else{
		$added_product_id = htmlentities(trim($_POST['id']));
		$added_product_size = htmlentities(trim($_POST['selected_size']));
		$key = $added_product_id . "|" . $added_product_size;
		if(array_key_exists($key, $_SESSION['cart'])){
		    $_SESSION['cart'][$key]++;
		}else{
		    $_SESSION['cart'][$key] = 1;
		}
	    }
	}
	
	require_once "../src/View/products/view.php";	
    }

    // view cart
    public function cart(){
	//remove items from cart
	if($_SERVER['REQUEST_METHOD'] === "POST"){
	    $key = htmlentities(trim($_POST['key']));
	    if(isset($_SESSION['cart'][$key])){
		unset($_SESSION['cart'][$key]);
	    }
	    
	    // Redirect to prevent form resubmission
	    header("Location: index.php?target=product&action=cart");
	}

	$cart_items = $this->getCartItemsFromSession();
	$total = $this->calculateCartTotal($cart_items);
	
	$productDao = new ProductDao();	
	require_once "../src/View/products/cart.php";	
    }


    public function getCartItemsFromSession(){
	return $_SESSION['cart'];
    }

    public function calculateCartTotal($cart_items){
	$total = 0;
	foreach($cart_items as $id => $amount){
	    $productDao = new ProductDao();
	    $cart_item = $productDao->getProductById($id);
	    $total += $cart_item->getPrice() * $amount;
	}
	return $total;
    }






}