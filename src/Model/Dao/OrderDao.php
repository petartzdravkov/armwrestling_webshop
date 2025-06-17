<?php

namespace Model\Dao;

use Exception;
use Model\Dao\AbstractDao;
use Model\Order;
use Model\Product;

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
		$size_id = $this->productDao->getSizeId($size);
		$sizes_in_stock = $this->productDao->getAmountByIdAndSize($id, $size_id);
		if($sizes_in_stock < $amount){
		    throw new \Exception("Not enough stock for '" . $cart_item->getName() . "', size '" . $size . "'.");
		}

		// add sold items to db
		$stmt_item->execute([$amount, $id, $order_id, $size_id]);

		// decrease stock in db
		$this->productDao->decreaseStock($id, $size, $amount);   
	    }

	    $this->getPdoConnection()->commit();
	}catch (\Exception $e){
	    $pdo->rollback();
	    throw new Exception($e->getMessage());
	}

    }

    public function getAllOrdersOfUser($user_id){
	$pdo = self::getPdoConnection();
	
	$sql = "SELECT * FROM dd_orders WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user_id]);
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	$orders = [];
	foreach($rows as $row){
	    $order = new Order();
	    $order->setDatetime($row['datetime']);
	    $order->setOrderId($row['id']);
	    $order->setStatus($row['status']);

	    $orders[] = $order;
	}
	
	return $orders;
    }

    public function getAllOrders(){
	$pdo = self::getPdoConnection();
	
	$sql = "SELECT * FROM dd_orders";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	$orders = [];
	foreach($rows as $row){
	    $order = new Order(null, $row['user_id']);
	    $order->setDatetime($row['datetime']);
	    $order->setOrderId($row['id']);
	    $order->setStatus($row['status']);

	    $orders[] = $order;
	}
	
	return $orders;
    }

    public function getAllSoldItemsFromOrder($order_id){
	$pdo = self::getPdoConnection();
	
	$sql = "SELECT * FROM dd_sold_items WHERE order_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$order_id]);
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	return $rows;
    }

    public function getOrderStatusByOrderId($order_id){
	$pdo = self::getPdoConnection();
	
	$sql = "SELECT status FROM dd_orders WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$order_id]);
	$status = $stmt->fetchColumn();

	return $status;
    }

    public function updateStatus($status, $order_id){
	$pdo = self::getPdoConnection();
	
	$sql = "UPDATE dd_orders SET status = ? WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$status, $order_id]);
    }












}