<?php

namespace Model\Dao;

use Model\Dao\AbstractDao;

class OrderDao extends AbstractDao{

    private $productDao;
    
    public function __construct($productDao){
	$this->productDao = $productDao;
    }

    public function save($order){
	$pdo = $this->getPdoConnection();
	try{
	    $pdo->beginTransaction();

	    // add order to db and store id
	    $sql = "INSERT INTO dd_orders (user_id, datetime, status) VALUES (?, NOW(), 'processing')";
	    $stmt_order = $pdo->prepare($sql);
	    $stmt_order->execute([$order->getUserId()]);
	    $order_id = $pdo->lastInsertId();
		
	    // prepare sold items for db, linking the order_id that was just created
	    $sql = "INSERT INTO dd_sold_items (amount, product_id, order_id, size_id) VALUES (?, ?, ?, ?)";
	    $stmt_item = $pdo->prepare($sql);

	    
	    // reduce amount of items in db
	    foreach($order->getCartItems() as $key => $amount){
		[$id, $size] = explode("|", $key);
		    
		// check if there is enough stock of this item and size
		$cart_item = $this->productDao->getProductById($id);
		$sizes_in_stock = $this->productDao->getAmountByIdAndSize($id, $size);
		if($sizes_in_stock < $amount){
		    throw new \Exception("Not enough stock for '" . $cart_item->getName() . "', size '" . $size . "'.");
		}

		// add sold items to db
		$stmt_item->execute([$amount, $id, $order_id, $this->productDao->getSizeId($size)]);

		// decrease stock in db
		$this->productDao->decreaseStock($id, $size, $amount);   
	    }

	    $this->getPdoConnection()->commit();
	    header("Location: index.php?target=checkout&action=success");
	    die;
	}catch (\Exception $e){
	    $pdo->rollback();
	    $payment_error = $e->getMessage();
	}

    }












}