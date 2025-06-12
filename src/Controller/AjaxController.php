<?php

namespace Controller;

use Model\Dao\ProductDao;

class AjaxController{

    // get available sizes for category
    public function getSizes(){
	$category = trim(htmlentities($_GET['category']));
	$product_id = isset($_GET['product_id']) ? trim(htmlentities($_GET['product_id'])) : false;
	$productDao = new ProductDao();
	$sizes = $productDao->getAllSizesByCategory($category);

	// returns when editing product
	if($product_id){
	    $size_amount = [];
	    foreach($sizes as $size){
		$size_id = $productDao->getSizeId($size);
		$size_amount[$size] = $productDao->getAmountByIdAndSize($product_id, $size_id);
	    }
	    echo json_encode($size_amount);
	}
	//returns when adding a product
	else{
	    echo json_encode($sizes);
	}

    }

    public function fillInfo(){
	$productId = trim(htmlentities($_GET['productId']));

	$productDao = new ProductDao();
	$product = $productDao->getProductById($productId);
	$product_array = [
	    "name" => $product->getName(),
	    "price" => $product->getPrice(),
	    "description" => $product->getDescription()
	];

	echo json_encode($product_array);
    }

    public function getAmountBySize(){
	$productId = trim(htmlentities($_GET['product_id']));
	$size = trim(htmlentities($_GET['size']));

	$productDao = new ProductDao();
	$size_id = $productDao->getSizeId($size);
	$amount = $productDao->getAmountByIdAndSize($productId, $size_id);

	echo $amount;	
    }


    
}