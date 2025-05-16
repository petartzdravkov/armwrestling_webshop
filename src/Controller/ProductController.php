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
	$pname = str_replace("-", " ", htmlentities(trim($_GET['pname'])));

	$productDao = new ProductDao();
	$product = $productDao->getProductByName($pname);
	
	require_once "../src/View/products/view.php";	
    }






}