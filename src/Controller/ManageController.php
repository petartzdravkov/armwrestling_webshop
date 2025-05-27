<?php

namespace Controller;

use Model\Dao\ProductDao;
use Model\Dao\OrderDao;
use Model\Dao\UserDao;

class ManageController{

    // manage products page
    public function products(){
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
		$value = htmlentities(trim($value));
		
		if($name == "update_status") continue;

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