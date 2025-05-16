<?php

namespace Controller;

use Model\Dao\ProductDao;

class ProductController{

    public function clothing(){
	$this->showProducts('clothing');

    }

    public function equipment(){
	$this->showProducts('equipment');	
    }

    public function showProducts($category){
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
	$og_pname = htmlentities(trim($_GET['pname']));
	$pname = str_replace("-", " ", $og_pname);

	$productDao = new ProductDao();
	$product = $productDao->getProductByName($pname);
	$available_sizes = $productDao->getAllSizesByProductId($product->getId());
	
	
	require_once "../src/View/products/view.php";	
    }






}