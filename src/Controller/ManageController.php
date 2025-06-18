<?php

namespace Controller;

use Exception;
use Helpers\Validator;
use Model\Product;
use Model\Dao\ProductDao;
use Model\Dao\OrderDao;
use Model\Dao\UserDao;

class ManageController{

    // manage products page
    public function products(){
	// users
	$userDao = new UserDao();
	
	// products
	$productDao = new ProductDao();

	if($_SERVER['REQUEST_METHOD'] === "POST"){
	    // if "Save" button has been pressed on any edit product modal
	    if(isset($_POST['save_edits'])){
		// image upload
		if(!empty($_FILES['img_path']['name'])){
		    $file_tmp = $_FILES['img_path']['tmp_name'];
		    $file_name = $_FILES['img_path']['name'];
		    $file_size = $_FILES['img_path']['size'];

		    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
		    $allowed = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
		    $maxSize = 3 * 1024 * 1024; //3mb

		    // File size check
		    if ($file_size > $maxSize) {
			throw new Exception("File is too large. Maximum size is 3MB.");
		    }

		    // File type check
		    if (in_array($_FILES['img_path']['type'], $allowed)) {
			$new_name = uniqid('img_', true) . '.' . $ext;
			$destination = "assets/images/products/" . $new_name;

			move_uploaded_file($file_tmp, $destination);

			// get product by id to update img path
			$productId = trim(htmlentities($_POST['productId']));
			$edited_product = $productDao->getProductById($productId);
			
			// delete old picture
			$old_name = $edited_product->getImgPath();
			if(file_exists($old_name)) unlink($old_name);
			
			// set new picture path and save to db
			$edited_product->setImgPath($destination);
			$productDao->updateProduct($edited_product);
		    } else{
			throw new Exception("Unsupported file type.");
		    }
		}

		// change all other info
		$name = htmlentities(trim($_POST['name']));
		$name = Validator::validateName($name, 50, "Product Name");
		$price = htmlentities(trim($_POST['price']));
		$price = Validator::validateInt($price, 0, 65534, "Price");
		$category = htmlentities(trim($_POST['category']));
		$category = Validator::validateEnum($category, ['clothing', 'equipment'], 'Category');
		$description = htmlentities(trim($_POST['description']));
		$description = Validator::validateDescription($description, "Description");
		$productId = htmlentities(trim($_POST['productId']));
		$productId = Validator::validateProductId($productId, $productDao);

		// prepare array with amounts for each size
		$sizes_amounts = [];
		foreach($_POST as $key => $value){
		    if (strpos($key, 'amount') !== false) {
			if(empty($value)) $value = 0;
			$sizes_amounts[explode('_', $key)[0]] = intval($value);
		    }else{
			continue;
		    }
		}

		if(empty($name) || empty($price) || empty($category) || empty($description) || empty($productId)){
		    throw new Exception("All values must be filled in");
		}else{
		    $product = $productDao->getProductById($productId);
		    $product->setName($name);
		    $product->setPrice($price);
		    //TODO fix hard coded value
		    $categoryId = $category == "clothing" ? 1 : 2;
		    $product->setCategoryId($categoryId);
		    $product->setDescription($description);
		    $productDao->updateProduct($product);
		}

		// save amount and sizes to db
		foreach($sizes_amounts as $size => $amount){
		    $size_id = $productDao->getSizeId($size);
		    $amount = Validator::validateInt($amount, 0, 16777214, "Amount for " . ucfirst($size));
		    $productDao->setAmountByProductIdAndSizeId($product->getId(), $size_id, $amount);
		}
		
		
		
		// if "Update Status" has been pressed
	    }elseif(isset($_POST['update_status'])){
		foreach($_POST as $name => $value){
		    $name = htmlentities(trim($name));
		    if($name == "update_status") continue;
		    $value = htmlentities(trim($value));
		    $value = Validator::validateEnum($value, ['draft', 'published', 'deleted'], 'Status');

		    $product_id = explode("_", $name)[1];
		    $current_product_status = $productDao->getProductStatusByProductId($product_id);
		    if($current_product_status !== $value){
			$productDao->updateStatus($value, $product_id);
		    }
		}

		// if "Save Product" has been pressed
	    }elseif(isset($_POST['save_new_product'])){
		// get user values
		$name = htmlentities(trim($_POST['name']));
		$name = Validator::validateName($name, 50, "Product Name");
		if($productDao->getProductByName($name)) throw new Exception("A product with this name already exists.");
		$price = htmlentities(trim($_POST['price']));
		$price = Validator::validateInt($price, 0, 65534, "Price");
		$category = htmlentities(trim($_POST['category']));
		$category = Validator::validateEnum($category, ['clothing', 'equipment'], 'Category');
		$description = htmlentities(trim($_POST['description']));
		$description = Validator::validateDescription($description, "Description");

		// prepare array with amounts for each size
		$sizes_amounts = [];
		foreach($_POST as $key => $value){
		    if (strpos($key, 'amount') !== false) {
			if(empty($value)) $value = 0;
			$sizes_amounts[explode('_', $key)[0]] = $value;
		    }else{
			continue;
		    }
		}


		// check if fields are empty
		if(empty($_FILES['img_path']['name']) || empty($name) || empty($price) || empty($category) || empty($description)){
		    throw new Exception("All fields are required");
		}else{
		    // image upload
		    $file_tmp = $_FILES['img_path']['tmp_name'];
		    $file_name = $_FILES['img_path']['name'];
		    $file_size = $_FILES['img_path']['size'];

		    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
		    $allowed = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
		    $maxSize = 3 * 1024 * 1024;

		    // File size check
		    if ($file_size > $maxSize) {
			throw new Exception("File is too large. Maximum size is 3MB.");
		    }

		    // File type check
		    if (in_array($_FILES['img_path']['type'], $allowed)) {
			$new_name = uniqid('img_', true) . '.' . $ext;
			$destination = "assets/images/products/" . $new_name;

			move_uploaded_file($file_tmp, $destination);
			
			// set new picture path and save to db
			$add_product = new Product();
			$add_product->setImgPath($destination);
		    }else{
			throw new Exception("Unsupported file type.");
		    }

		    // all other product info
		    // set values in Product entity
		    $add_product->setName($name);
		    $add_product->setPrice($price);
		    //TODO fix hard coded value
		    $categoryId = $category == "clothing" ? 1 : 2;
		    $add_product->setCategoryId($categoryId);
		    $add_product->setDescription($description);

		    // save to db
		    $productDao->createProduct($add_product);

		    // save amount and sizes to db
		    foreach($sizes_amounts as $size => $amount){
			$size_id = $productDao->getSizeId($size);
			$amount = Validator::validateInt($amount, 0, 16777214, "Amount for " . ucfirst($size));
			$productDao->setAmountByProductIdAndSizeId($add_product->getId(), $size_id, $amount);
		    }
		}
	    }
	}

	$products = $productDao->getAllProducts();
	require_once("../src/View/manage/products.php");
    }

    // manage orders page
    public function orders(){
	// users
	$userDao = new UserDao();
	
	// orders
	$productDao = new ProductDao();
	$orderDao = new OrderDao($productDao);
	$orders = $orderDao->getAllOrders();

	// if "Update Status" has been pressed
	if($_SERVER['REQUEST_METHOD'] === "POST"){
	    foreach($_POST as $name => $value){
		$name = htmlentities(trim($name));
		if($name == "update_status") continue;
		$value = htmlentities(trim($value));
		$value = Validator::validateEnum($value, ['processing', 'shipped'], 'Status');		

		$order_id = explode("_", $name)[1];

		$current_order_status = $orderDao->getOrderStatusByOrderId($order_id);
		if($current_order_status !== $value){
		    $orderDao->updateStatus($value, $order_id);
		}

	    }

	}
	
	require_once("../src/View/manage/orders.php");
    }
}